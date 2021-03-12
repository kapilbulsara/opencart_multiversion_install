# Create Opencart Test Environment 

This is a php script that will clone the opencart repository on github and create multiple repositories from git tags. It will then perform install on each of the versions of opencart. 
This is useful for plugin developers who want to test their plugins on multiple versions of opencart. 
NOTE: This script will not work properly for 1.x versions of opencart on php  7 and up. You may try to run this script on php 5 to install older versions of opencart. It will however still create and download all the files in the appropriate version folders. 

## Support

Please, if you are having issues, *do not* post comments on opencart. You will *not* get a response. Please email all enquiries to support@okinara.com

## Requirement 
- php composer
- php 7 
- php-mysqli 
- php-mcrypt
- mysql/mariadb
- git 
- Linux (it may work on Windows and MacOS, however there are no guarantees)
- Apache 2.x web server 


## Usage 

Copy the create_test_env.php file in  the directory where all your opencart sites will be and run the following command. Please option values with your own. 

```
php create_test_env.php  --db_hostname localhost \  
                         --db_username demo \
						 --db_password pass \
						 --db_prefix demo_opencart_  \
						 --username admin \
						 --password admin \
						 --email yourmail@example.com \
						 --http_server http://localhost/opencart/


```

All options must be provided.  Please ensure that the database user has the right privileges to create databases. 

* --db_prefix - is the prefix for the database names. For e.g. if demo_opencart_ is the prefix. Datbase names will be in the form demo_opencart_2021, demo_opencart_2022 etc...
* --http_server - this will be your main page from where you will access all different versions of opencart. 

Example, if --http_server is set to  http://localhost/opencart/ then  version 2.0.3.1 will be at http://localhost/opencart/2.0.3.1/upload/ 

*NOTE* - you *MUST* include / (forward slash) at the end of your --http_server

## Known Issues

### composer
You may have some issues with php composer. If so, please, delete the vendor folder at upload/system/storage/vendor
then run `composer -n update` in the root directory of that version of opencart. 

### missing plugins
Please make sure that all php plugins are installed. 

### Versions 1.x
All files will be created, however, database installation will not work with newer versions for php. It is possible to install opencart versions 1.x if you have an older version of php. 

## Free Extras

I have included  delete all folders and drop all database script. This is in case you want to start from scratch again. 
These will only work on Linux. 

`sudo bash  deleteFolders` - will delete all opencart version folders
`sudo mysql < dropDBs.sql` - will drop all opencart databases. If you have disabled unix_socket plugin you may want to log in mysql and then `use dropDBs.sql`
