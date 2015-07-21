#1. Installation

###1.1. Requirements:

* _Make sure that `git` is installed on your system. Check the GitHub's Setting Up Git guide [here](https://help.github.com/articles/set-up-git).
* _Make sure `composer` is installed. FInd out more for linux [here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) and for windows [here](https://getcomposer.org/doc/00-intro.md#installation-windows).
* _Make sure a database connection is available (only tested with MySQL.. should work with any DB supported by both doctrine and CodeIgniter)_ 

###1.2. Clone repository

 Clone the repository by running
 `git clone https://github.com/Vlad-Iliescu/ci3_twig_doctrine2.git`
 on your machine.

###1.3. Install vendor dependencies

 run `composer install` to create the `/vendor` folder

###1.4. configure framework for dev environment

The basic configuration files needed for the setup are:

`./application/config/database.php`

`./application/config/doctrine.php`

`./application/config/twig.php`

`./application/config/php_debug_bar.php`

Check out each file on details on how to configure. Note that Doctrine uses CI's ActiveRecord configuration yo create a database connection, so make sure that the `database.php` file is configured properly. Also checkout CI's user manual on how to configure the database connection.
 
###1.5. Production setup

#TODO
 
####Notes

As you may notice, CodeIgniter's default `subclass_prefix` was change from `MY_` to `Base_` so take this into account.

