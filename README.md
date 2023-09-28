# Shop list project
A RESTful API that supports a list of shops in Greece, made by using the [Symfony](https://symfony.com/doc/current/index.html) framework.



# Requirements
The below technologies/tools are needed to run this project

1. PHP 8.1
2. MySQL 8.0
3. Latest version of [Composer](https://getcomposer.org/)

# Application Installation
1. `git clone` the repo
2. `cd my-project/`
3. Run `composer install`
4. Copy the `.env` file in the root folder and generate a `.env.local` file.
5. If necessary, change the database credentials to the new file.

# Database Setup
You can choose between two ways to setup the database:

1. Either use the MySQL dump file that was provided to you.
2. Or, at the root folder of the application, run `php bin/console doctrine:migration:migrate`, and generate your own data using the postman collection that was provided to you.