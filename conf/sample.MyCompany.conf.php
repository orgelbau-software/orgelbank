<?php
define('INSTALLATION_NAME', 'BEISPIEL');

define('MYSQL_HOST', '');
define('MYSQL_USER', '');
define('MYSQL_PASS', '');
define('MYSQL_DB', '');

define('ROOTDIR', $_SERVER['DOCUMENT_ROOT']);
define('TMPDIR', ROOTDIR . "/tmp/");
define('BACKUPDIR', ROOTDIR . "backup/");
define('INSTANCE_URL', "https://localhost/");

define('SUPPORT_MAIL_ENABLED', true);
define('GOOGLE_MAPS_API_KEY', '');
define('ORGELBANK_API_KEY', '');

define('LOGENABLED', false);
define('SUPPORT_MAIL_FROM', "system@kunde.de");

// Der Salt wird zum Speichern der Passwort Hashes in der Datenbank verwendet und mach den Hash etwas sicherer.
define('PASSWORD_SALT', 'abc');

// Prefix fuer die Rechnungs Templates in resources/vorlagen/kunde_rechnung_pflege.docx
define('RECHNUNG_PREFIX', 'kunde_');
?>