pub fn add(left: usize, right: usize) -> usize {
    left + right
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
    fn t2() {
        use heapless::FnvIndexSet;
        //use std::slice::range;
        //use core::slice::range;

        // src: https://japaric.github.io/heapless/heapless/struct.IndexSet.html
        // A hash set with a capacity of 16 elements allocated on the stack
        let mut books = FnvIndexSet::<_, 16>::new();

        // Add some books.
        books.insert("A Dance With Dragons").unwrap();
        books.insert("To Kill a Mockingbird").unwrap();
        books.insert("The Odyssey").unwrap();
        books.insert("The Great Gatsby").unwrap();

        /*let mut s:String;
        for i in 0..=116+1 { //range(0,118) {
            s=i.to_string();
            books.insert(&s);
        }*/

        // Check for a specific one.
        if !books.contains("The Winds of Winter") {
            println!(
                "We have {} books, but The Winds of Winter ain't one.",
                books.len()
            );
        }

        // Remove a book.
        books.remove("The Odyssey");

        // Iterate over everything.
        for book in &books {
            println!("{}", book);
        }
    }

    #[test]
    fn kept_insertion_order_ints() { //it doesn't keep insertion order! (but wasn't clear, https://github.com/japaric/heapless/issues/321 )
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

        for j in &nums {
            println!("now: {j}");
        }

        let mut i: Numbers = FIRST;
        let mut iter = nums.iter();
        for j in &nums {
            if let Some(cur) = iter.next() {
                println!("{:?}", cur);
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
