# Getting Started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.
You'll need the following tools installed on your computer:
- [Git](https://git-scm.com/)
- [PHP >= 8.2](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/)
- [XAMPP](https://www.apachefriends.org/index.html)
- [MySQL](https://dev.mysql.com/downloads/mysql/)
- [MySQL Workbench](https://www.mysql.com/products/workbench/)

# How To Use
```bash
# Clone this repository
git clone https://github.com/ManDuong25/ShoesStore.git

# Setup evironment to run web on local server

# With Windows
move the repository into C:\xampp\htdocs

# With Linux
move the repository into /var/www/html

# Go into the repository
cd ShoesStore

# Composer Setup 
composer install

# Create Database with database management tool
Run file init in ShoesStore/sql/init.sql
Run file insert in ShoesStore/sql/insert.sql

#Custom database connection
Custom file database_connection.php in ShoesStore/backend/services/database_connection.php

#Open web in local
type localhost/ShoesStore on google search bar
``` 
# Technology Used
- HTML
- CSS
- Javascript
- PHP
- MySQL

# Project Maintainers
- [ManDuong](https://www.linkedin.com/in/man-duong-5b360132a/)

# Contributors
- Cong Man
- Anh Danh
- Hoang Quyen
- Mai Anh
- Mai Trinh
- Yen Phuong
- Danh Tien
