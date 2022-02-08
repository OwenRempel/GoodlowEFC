# GoodlowEFC

This is the code base for the Goodlow EFree Church

Some features include:
- PHP Routing on the front end
- API for easy scalability
- PHP based RESTful API, [Source](https://github.com/OwenRempel/GoodlowEFC/blob/main/API/)
- Admin app built in React, [Source](https://github.com/OwenRempel/GoodlowAdmin)


Simply Place this folder in any php server and add the file secrets.php in the <code>API/DB</code> folder with the following.

```php
$ADMIN_SECRET_KEYS=[
    'username'=>'Username',
    'password'=>'YourPassword'
];

$YOUTUBE_API_KEY='000000000000000000000000000000';

$VIMEO_API_KEY='00000000000000000000000000000000';
```
Create Main Database
```SQL
CREATE DATABASE GoodlowEFC;
```
Create The Admin User
```SQL
CREATE USER 'GoodlowAdmin'@'localhost' IDENTIFIED BY 'YourPassword';
```
And Grant all Privileges
```SQL
GRANT ALL PRIVILEGES ON GoodlowEFC. * TO 'GoodlowAdmin'@'localhost';
```
You will have to update your <code>/etc/apache2/apache2.conf</code>
to allow for the <code>.htaccess</code> and routing to work correctly.

```bash
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>

```
As well as enable the rewrite module.

```bash
a2enmod rewrite
```
