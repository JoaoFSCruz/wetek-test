# WeTek test

## Running the code

- Clone the repository
- Run `composer install`
- Set up the `.env` file accordingly
- Run `symfony server:start`
- That's it!

To add dummy data, I have implemented a Data Loader class for Products. You just need to run:

`php bin/console doctrine:fixtures:load`