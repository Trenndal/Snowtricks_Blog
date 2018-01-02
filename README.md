---
---
# Snowtricks_Blog  v0.1
### A Symfony project created on November 29, 2017, 8:42 pm.
---
---
Symfony 3 web site who propose an communitarian listing of snowboard tricks.

---
### Prerequisites
- Server PHP 7 with admin right.
- SQL Database with admin right.
- Composer installed.
---
### Installing
1. Download this project and his dependency with composer. 
```bash 
php composer create-project Trenndal/Snowtricks_Blog --repository-url="https://github.com/Trenndal/Snowtricks_Blog" 
```
2. Modify the *app/config/parameters.yml* file. 
```ini 
parameters:
    database_host: IP or address ex: 127.0.0.1
    database_user: Your_username
    database_password: Your_password or null
    mailer_transport: smtp or pop
    mailer_host: 'your.host.address'
    mailer_user: 'Your_username'
    mailer_password: 'Your_password'
    mailer_port: Port number 
```
3. Generate the Database :
```bash 
php bin/console doctrine:database:create 
php app/console doctrine:schema:update --force
``` You can load a snowtricks set with the URL : 'siteURL/installer' .
5. [Deploy the project to production mode](https://symfony.com/doc/current/deployment.html)
Modify file *.htaccess* :
```ini 
    # Change below before deploying to production
    RewriteRule ^(.*)$ web/app.php [QSA,L]
    #RewriteRule ^(.*)$ web/app_dev.php [QSA,L]
```

