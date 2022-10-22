//Note: ID would be made out of a random number and its hash(eg. sha256) but salted hash, and the
//salt would be a random per-database one, chosen when the database is first created!
//
//This would detect any potential corruption due to memory errors for example and also maybe tag
//the IDs so they're known to come from a certain database.
//
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
