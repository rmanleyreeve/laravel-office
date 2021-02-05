# laravel-office

Laravel port of SME office employee time management system

PHP backend originally developed on [F3](https://github.com/bcosca/fatfree)
UI is AdminBSB-Sensitive by @gurayyarar using Bootstrap 3 & jQuery.

Porting to Laravel 8 & building Docker image as part of a quick Laravel refresher.
Session-based authentication and user roles ported across to custom middleware using Laravel auth classes.
Legacy functions rebuilt using Laravel classes

![image 1](resources/screenshots/screenshot1.png)
![image 1](resources/screenshots/screenshot2.png)
![image 1](resources/screenshots/screenshot3.png)

## Installation  

(Requires bower and npm)

- Clone the repo and run the following:
    ```sh
    $ composer update
    $ npm install
    $ npm run dev
    ```
- Set your DB connection details in `.env`
- Create and populate the databse:
    ```sh
    $ php artisan migrate:fresh --seed
    ```
- Start the server:
    ```sh
    $ php artisan serv
    ```
The app should now be running on http://127.0.0.1:8000  
Log in with username: "test" password: "test"
