//will use fifo_hashset to keep a list of say 100 last random numbers and make sure the newly
//generated one isn't in the set*, then push it to the set and thus have the fifo pop out the oldest
//one if set len is now > 100.
//
//*if it is then gen a new one**, if debug maybe keep a list of newly generated numbers and the set
//itself at the time, and on program exit report everything?
//** only gen a new one X number of consecutive times, else risk infinite loop! X can be 100 let's
//say. This would detect broken random number generator.(but also add a test for this!)
//
//should also check if all bits are 0 or 1 of the newly generated random number
pub fn add(left: usize, right: usize) -> usize {
    left + right
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn it_works() {
        let result = add(2, 2);
        assert_eq!(result, 4);
    }
}
