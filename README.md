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
6. Reset the Fonts
* https://customer.orgelbau-software.de/vendor/setasign/tfpdf/font/unifont/ttfonts.php