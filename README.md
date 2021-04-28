# bilog
## _Backend Data Circle_

This project was built with [Laravel](https://laravel.com/) version 8.35.1.<br>
This project was made to serve as the REST endpoint for [IT Log](https://github.com/bossbuwi/itlog). The version releases are [here](https://github.com/bossbuwi/bilog/releases). Current version is 1.0-Argon and is ready for production. This project is just the back end for an event logging app. For this to function completely, a compatible front end that consumes data using a REST endpoint must be used, in this case, [IT Log](https://github.com/bossbuwi/itlog).

### Setup for Development
Note: These instructions assume that XAMPP is used as the development server. As such, the PHP CLI is also provided by XAMPP. If you are using a different setup, please adjust the instructions accordingly.

#### Prerequisites
- An editor, preferably VS Code

#### Installing XAMPP
1. Download XAMPP installer from [here](https://www.apachefriends.org/index.html).
2. Run the installer and install on desired directory. Just be sure to remember where it is installed.

#### Installing Composer
Note: XAMPP must be installed before installing Composer because Composer needs a PHP command line interface to be installed properly. XAMPP provides the PHP CLI for this setup. If you already have a different PHP CLI installed, then proceed even without XAMPP and adjust the instructions accordingly.
1. Download Composer from [here](https://getcomposer.org/download/).
2. Run the installer.
3. When prompted for a PHP command line interface, select the php.exe on `<XAMPP installation directory>/php/php.exe`.
4. To check if Composer is properly installed, open a command prompt window and enter `composer -v`.

#### Installing Laravel
1. Download the Laravel installer using Composer by entering `composer global require laravel/installer` on a command prompt window.

#### Setting up the Repository
1. Clone the repository from Github.
2. Open a command prompt window on the repository's root directory.
3. Install the Laravel dependencies by entering `composer install` on the command prompt.
4. Copy the .env.example file and rename the copy into .env. This could be done manually or by entering `copy .env.example .env` on the command prompt.
5. Generate the application key needed by Laravel by entering `php artisan key:generate` on the command prompt.
6. Enter `php artisan server` on the command prompt to start the development server.
7. Go to the address shown after `Starting Laravel development server:` on the command prompt. If Laravel is working, a page must be displayed.

#### Setting up the Database
1. Run XAMPP.
2. Start the Apache and MySQL modules.
3. Go to the MySQL admin panel by clicking the admin button on the MySQL row on XAMPP.
4. Click the Databases tab.
5. On the Create Database input field, type `db_bilog`. This would be the database used by bilog.
6. Click Create button to finish the database creation.
7. Open the .env file on the repository's root folder using a text editor.
8. Find the line `DB_DATABASE` and set it to `db_bilog`.
9. If you have a different MySQL user and password, set it on the `DB_USERNAME` and `DB_PASSWORD`. If you are using the default XAMPP MySQL passwords, just leave it as is.
10. Find `DB_PORT` and check if it is the same as the port used by MySQL on XAMPP.
11. Open a command prompt window on the repository's root directory.
12. Enter `php artisan migrate` to run all of the migrations set by the project. This will create several tables on the `db_bilog` database.
13. Enter `php artisan db:seed` to seed the tables with the initial values set by the project. This will create the default records on the database as specified on the seeder files.
14. Go to `http://localhost:<apache http port>/phpmyadmin/` and check the `db_bilog` database if the tables and records have been added.

#### Running the Development Server
1. Run XAMPP and start the Apache and MySQL modules.
2. Open a command prompt on the project's root directory.
3. Enter `php artisan serve` to start the Laravel server.
