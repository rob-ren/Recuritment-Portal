# PHP-RecruitingPortal

Recruiting Portal is a small handy system for recruiting and interview purpose.

## Getting Started

We are using laravel 5.6 with php7.1 and mysql 5.7 to deploy this project. please make sure you install those on your local machine.

### Prerequisites

- Please install the PHP version 7.1
```
brew update
brew install php71
```
(please install brew first by the link: https://brew.sh/)

- Please install mysql version 5.7 on your local machine
```
brew update
brew install mysql@5.7
```
* Hint: The Laravel 5.6 has some connection issues with mysql8. If you are using mysql8, please see the following link to solve the issues. https://github.com/laravel/framework/issues/23961

### Installing

- git clone the project repo and get into your project folder

- using composer to download and update your vendor resources

```
composer update
```
(please install composer first by the link: https://getcomposer.org/doc/00-intro.md)

- set up the `.env` file from `.env.example` into your project folder. Please make sure that you set up the correct system environment and pass the parameters into `.env` file

- update the database schema to your database

```
php artisan migrate
```
- import the demo data into the database

```
php artisan db:seed
```
* Hint: System disable the register function and we create the admin user by import from DatabaseSeeder.php file. Please consult benny.sheerin@appscore.com.au for the username and password

- start up the server and you will see the login page :)

```
php artisan serve
```

## Deployment

- Follow the previous steps for deployment environment

- Add `.htaccess` file under the project folder
```
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## Built With

* [Laravel 5.6](https://laravel.com/docs/5.6) - The web framework used
* [DataTable](https://datatables.net/) - Table Plugin for Jquery
* [Typeahead.js](https://twitter.github.io/typeahead.js/examples/) - Typeahead tools for Jquery
* [Datepicker](https://github.com/eternicode/bootstrap-datepicker/) - Date picker tools for Bootstrap

## Authors

* **Robert Ren** https://github.com/robertren1
