# Margarson
This project belongs to Oliver Margarson and entails the mapping of UK postcodes per area and district.

### Installation 
Folders attached are 
- postcodes folder that contains all files
- ukpostcodes.sql - a script to setup the database. 
- Download it from ```php
- nakuplan.com/private/ukpostcodes.sql```

### Customise
This project runs on localhost, you will have to change some configurations for it to work for you.
### Steps 

#### Init.php 
located in the includes folder. Change the details below.

Type your postgresql database name
``` php
$dsn = "pgsql:host=localhost;dbname=******;port=5432";
```
Add password of your postgresql database
``` php
$pdo = new PDO($dsn, 'postgres', '*********', $opt);
```
Edit root directory to yours.
``` php
 $root_directory = "******";
 $from_email = "admin@che.com";
 $reply_email = "admin@che.com";
 include "php_functions.php";
 ```
#### SQL 
Ensure to execute the PostGIS extension

```sql
CREATE EXTENSION postgis;
```
1.Run the SQL script in your PostgreSQL interface of your desired database. 
Check your database by refreshing it.


### Accessing your file 
- Access file via URL.
 ```php
localhost:8080/postcodes/content/index.html
```
OR 
- When you open localhost, there is a start-page that has a URL. Edit as shown below.

 ```php
localhost:8080/*****/*****/*****
```

