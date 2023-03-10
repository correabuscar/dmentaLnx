#![deny(clippy::all, clippy::pedantic, clippy::nursery, warnings, future_incompatible, nonstandard_style,
        non_ascii_idents, clippy::restriction, rust_2018_compatibility, rust_2021_compatibility, unused)]
#![allow(clippy::print_stdout, clippy::use_debug, clippy::missing_docs_in_private_items)]

#![allow(clippy::blanket_clippy_restriction_lints)] //workaround clippy

#![allow(clippy::needless_return)]

// might want to deny later:
#![allow(clippy::default_numeric_fallback)] // might want to deny later!
#![allow(clippy::dbg_macro)]

#![feature(stmt_expr_attributes)]

#[inline] #[must_use]
pub const fn add(left: usize, right: usize) -> usize {
    #[allow(clippy::arithmetic_side_effects)]
    #[allow(clippy::integer_arithmetic)]
    return left + right;
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn it_works() {
        let result = add(2, 3);
        assert_eq!(result, 5);
    }

    #[test]
    fn kept_insertion_order_ints() {
        //keeps insertion order as long as remove() is called on last item(s), or not called at
        //all. See: https://github.com/japaric/heapless/issues/321
        use heapless::FnvIndexSet;
        const LAST: usize = 128; //must be power of two, FnvIndexSet requirement
        type Numbers = u32;
        let mut nums = FnvIndexSet::<Numbers, LAST>::new();
        const FIRST: Numbers = 1;
        const THROWER: Numbers = 1000;
        use std::ops::RangeInclusive;
        //const DELETER:RangeInclusive<Numbers>=(FIRST+102)..=(LAST as Numbers - 5);
        const DELETER: [RangeInclusive<Numbers>; 3] = [
            (FIRST + 10)..=(FIRST + 25),
            (FIRST + 50)..=(FIRST + 85),
            (FIRST + 102)..=(LAST as Numbers - 5),
        ];
        const PICKER: Numbers = 100;
        for i in FIRST..=LAST as Numbers {
            let r: Result<bool, Numbers>;
            r = if i == PICKER {
                nums.insert(THROWER)
            } else {
                nums.insert(i)
            };
            //println!("{r:?}");
            assert_eq!(r, Ok(true));

            //having removal done here doesn't change order of existing items:
            for all_d in DELETER {
                if all_d.contains(&i) {
                    let b = nums.remove(&i);
                    assert_eq!(b, true);
                }
            }
        }

        //but having removal done here, does change the order of existing items:
        /*for i in FIRST..=LAST as Numbers {
            for all_d in DELETER {
                if all_d.contains(&i) {
                    let b = nums.remove(&i);
                    assert_eq!(b, true);
                }
            }
        }*/

        /*for j in &nums {
            println!("now: {j}");
        }*/

        let mut i: Numbers = FIRST;
        let mut iter = nums.iter();
        for j in &nums {
            if let Some(cur) = iter.next() {
                //println!("{:?}", cur);
                if i == PICKER {
                    assert_eq!(*cur, THROWER);
                    assert_eq!(*j, THROWER);
                } else {
                    for all_d in DELETER {
                        while all_d.contains(&i) {
                            i += 1;
                        }
                    }
                    assert_eq!(*j, i);
                    assert_eq!(*cur, i);
                }
            }

            i += 1;
        }
    }

    #[test]
    fn doesnt_keep_insertion_order_after_remove_ints() {
        //it doesn't keep insertion order! (but wasn't clear, https://github.com/japaric/heapless/issues/321 )
        use heapless::FnvIndexSet;
        const LAST: usize = 128; //must be power of two, FnvIndexSet requirement
        type Numbers = u32;
        let mut nums = FnvIndexSet::<Numbers, LAST>::new();
        const FIRST: Numbers = 1;
        const THROWER: Numbers = 1000;
        use std::ops::RangeInclusive;
        //const DELETER:RangeInclusive<Numbers>=(FIRST+102)..=(LAST as Numbers - 5);
        const DELETER: [RangeInclusive<Numbers>; 3] = [
            (FIRST + 10)..=(FIRST + 25),
            (FIRST + 50)..=(FIRST + 85),
            (FIRST + 102)..=(LAST as Numbers - 5),
        ];
        const PICKER: Numbers = 100;
        for i in FIRST..=LAST as Numbers {
            let r: Result<bool, Numbers>;
            r = if i == PICKER {
                nums.insert(THROWER)
            } else {
                nums.insert(i)
            };
            //println!("{r:?}");
            assert_eq!(r, Ok(true));

            //having removal done here doesn't change order of existing items:
            /*for all_d in DELETER {
                if all_d.contains(&i) {
                    let b=nums.remove(&i);
                    assert_eq!(b,true);
                }
            }*/
        }

        //but having removal done here, does change the order of existing items:
        for i in FIRST..=LAST as Numbers {
            for all_d in DELETER {
                if all_d.contains(&i) {
                    let b = nums.remove(&i);
                    assert_eq!(b, true);
                }
            }
        }

        /*for j in &nums {
            println!("now: {j}");
        }*/

        let mut i: Numbers = FIRST;
        let mut iter = nums.iter();
        let mut diff = 0;
        for j in &nums {
            if let Some(cur) = iter.next() {
                //println!("{:?}", cur);
                if i == PICKER {
                    //assert_eq!(*cur, THROWER);
                    //assert_eq!(*j, THROWER);
                    #[allow(unused_parens)]
                    let eq1 = (*j != THROWER);
                    #[allow(unused_parens)]
                    let eq2 = (*cur != THROWER);
                    assert_eq!(eq1, eq2);
                    if *j != THROWER {
                        diff += 1;
                    }
                } else {
                    for all_d in DELETER {
                        while all_d.contains(&i) {
                            i += 1;
                        }
                    }
                    //assert_eq!(*j, i);
                    #[allow(unused_parens)]
                    let eq1 = (*j != i);
                    #[allow(unused_parens)]
                    let eq2 = (*cur != i);
                    assert_eq!(eq1, eq2);
                    #[allow(unused_parens)]
                    if (*j != i) {
                        diff += 1;
                    }
                    //assert_eq!(*cur, i);
                }
            }

            i += 1;
        }
        assert!(diff > 0);
    }

    /*#[test]
    fn kept_insertion_order_of_my_type() {
        use heapless::FnvIndexSet;
        const LAST: usize = 128; //must be power of two, FnvIndexSet
                                            //limitation/requirement
        //type Numbers = u32;//works but can use 'u32' instead by mistake.
        #[derive(Eq,PartialEq,Hash,Debug)]
        struct Numbers(u32);
        let mut nums = FnvIndexSet::<Numbers, LAST>::new();
        const FIRST:Numbers=Numbers(1);
        const THROWER:Numbers=Numbers(1000);
        const PICKER:Numbers=Numbers(100);
        for i in FIRST..=LAST as Numbers {
            let r: Result<bool, Numbers>;
            r=if i==PICKER {
                nums.insert(THROWER)
            } else {
                nums.insert(i)
            };
            //println!("{r:?}");
            assert_eq!(r,Ok(true));
        }
        let mut i:Numbers = FIRST;
        let mut iter=nums.iter();
        for j in &nums {
            assert_eq!(*j, i);

            if let Some(cur)=iter.next() {
                println!("{:?}", cur);
                if i==PICKER {
                    assert_eq!(*cur,THROWER);
                } else {
                    assert_eq!(*cur,i);
                }
            }

            i += 1;
        }
    }
    */
}
