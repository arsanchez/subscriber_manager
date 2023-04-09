# Mailerlite subscriber Manager

## How to run this project 

- Clone this repo 

- Open a terminal window on the root folder of the repo 

- Install PHP dependencies by running `composer install`

- Install JS dependencies by running  `npm install`

- Import the db script in the ***subscribers.sql*** file located in the ***db*** folder using phpMyAdmin or any other Mysql client

- Rename the .env.example file to .env by running `mv .env.example .env`

- Set up your db connection details, the db name is  subscribers

  `DB_CONNECTION=mysql`

  `DB_HOST=127.0.0.1`

  `DB_PORT=3306`

  `DB_DATABASE=subscribers`

  `DB_USERNAME=root`

  `DB_PASSWORD=`

- Run the php server by opening a new terminal window in the project's root folder and running `php artisan serve`

- Build the assets by opening a new terminal window in the project's root folder and running `npm run dev`

- The app should now be available at http://localhost:8000/

## Running the test 

- Open a terminal window on the root folder of the repo 
- Run the following command `php artisan test`