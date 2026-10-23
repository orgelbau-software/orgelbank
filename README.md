# README #

This is the README of the first GIT Package of the famous Orgelbank Project

### FPDF
The "Wartungsbogen" is using custom fonts. In order to use them, you have to make the directory writeable.
Then delete the pre-configured PHP files, like
* dejavusanscondensed.mtx.php
* dejavusanscondensed-bold.mtx.php
* dejavusanscondensed-bold.cw127.php
* dejavusanscondensed.cw127.php
* dejavusanscondensed.cw127.dat
* dejavusanscondensed-bold.cw.dat

Then manually execute the script in the browser to re-generate the files. Maybe two executions are required...:
* https://domain.com/vendor/setasign/tfpdf/font/unifont/ttfonts.php


# Create a fresh installation
1. Upload all Files to your FTP Server
2. Configure Lets Encrypt Certificates
3. Configure PHP Version 8.3
4. Run Composer with the correct PHP version
*  /usr/bin/php83 /usr/bin/composer install
5. Create or Reset the Database
* If you apply a database backup, remove the line to create the "rechung_view" first. Otherwise indices will not be created.
6. Database Modifications
- Reset Database: http://customer.yourdomain.de/resources/reset/reset.database.php
- Align API_KEY from your config.php file with the API key in OPTION_META table
- Re-Create Rechnungs-View with resources/db/create_rechnung_view.sql
7. Reset the Fonts
Delete the pre-configured PHP files, like
* https://customer.orgelbau-software.de/vendor/setasign/tfpdf/font/unifont/
* dejavusanscondensed.mtx.php
* dejavusanscondensed-bold.mtx.php
* dejavusanscondensed-bold.cw127.php
* dejavusanscondensed.cw127.php
* dejavusanscondensed.cw127.dat
* dejavusanscondensed-bold.cw.dat
* https://customer.orgelbau-software.de/vendor/setasign/tfpdf/font/unifont/ttfonts.php
8. Configure Cronjobs
* Weekly Sunday: Database Backup: https://customer.orgelbau-software.de/src/cronjobs/db_backup.php
* Weekly Sunday: Weekly Tasks: https://customer.orgelbau-software.de/src/cronjobs/cronjob.php?key=<API KEY DEFINED IN CONFIG.PHP>

# Autoupdate
Updates via GIT are recommmended.
```
    git clone https://github.com/orgelbau-software/orgelbank.git
    mv orgelbank/.git .
    mv orgelbank/.gitignore .
    mv orgelbank/* .
    cp backup-swa-20240918/conf/xxx.yyy.conf.php conf/
    vi conf/config.inc.php
    
    # File needs execute permission to run GIT commands
    chmod 775 autoupdate.php
    
    # Call git pull OR
    git pull 
    
    # Call 
    https://yourdomain.de/autoupdate.php
```

# Ubuntu 
Install LAMP stack on Ubuntu 26.04 to run the software
```
sudo apt install php83
sudo apt install libapache2-mod-php
sudo apt install php-mbstring
sudo apt install php8.3 php8.3-cli php8.3-common php8.3-mbstring
sudo apt install php-cli php-mbstring php-xml php-curl
sudo apt install php-gd
sudo apt install php-zip unzip
sudo apt install php-mysql
sudo systemctl restart apache2

cd /tmp
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
cd
cd sandboxes/orgelbank/

php /usr/local/bin/composer update

sudo apt install phpmyadmin -y
mysql -u root

mysql -u swatermeyer -p
```