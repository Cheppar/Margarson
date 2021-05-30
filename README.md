# Margarson
This project belongs to Oliver Margarson and entails the mapping of UK postcodes per area and district.

## Installation 
Folders attached are 
- postcodes folder that contains all files
- margarson.sql - a sript to setup the database 

## Customise
This project runs on localhost, you will have to change some configurations for it to work for you.
### Steps 
init.php file
Type your postgresql database name
``` php
$dsn = "pgsql:host=localhost;dbname=******;port=5432";
```
Add password of your postgresql database
``` php
$pdo = new PDO($dsn, 'postgres', '*********', $opt);
```
Edit root directory yours.
``` php
 $root_directory = "******";
    $from_email = "admin@che.com";
    $reply_email = "admin@che.com";
    include "php_functions.php";
 ```
