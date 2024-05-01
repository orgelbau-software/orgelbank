<?php
//config.php
date_default_timezone_set("Europe/Berlin");
setlocale(LC_ALL, 'de_DE.utf8');

mb_internal_encoding( 'UTF-8' );

// Providerspezifische Zugangsdaten laden
//include_once 'example.php';
//include_once 'bente.allinkl.conf.php';
//include_once 'fo.all-inkl.conf.php';//
include_once 'fo.lokal.conf.php';
//include_once 'krawinkel.allinkl.conf.php';
//include_once 'krawinkel.lokal.conf.php';


if((!is_dir(ROOTDIR) || !substr(ROOTDIR, -strlen(ROOTDIR)) === "/")) {
    die("Konstante ROOTDIR zeigt auf ein ungueltiges Verzeichnis. Das Verzeichnis muss mit / enden. " . ROOTDIR);
}

if (version_compare(phpversion(), '8.2', '<')) {
    die("The PHP Version is too low (".phpversion()."). Required is 8.3");
} elseif (version_compare(phpversion(), '8.4', '>')) {
     die("The PHP Version is too high (".phpversion()."). Required is 8.3");
}


// Konstanten
define('TRACEENABLED', false);

define('SUPPORT_MAIL_ADDR', "stephan@watermeyer.info");

define("ORGELBILD_BILD_PFAD", ROOTDIR . "store/orgelpics/");
define("ORGELBILD_THUMB_PFAD", ORGELBILD_BILD_PFAD . "thumbs/");
define('RECHNUNGDIR', ROOTDIR . 'store/rechnungen/');

define("WARTUNGSPROTOKOLL_RELATIVER_PFAD", "store/protokolle/");
define("WARTUNGSPROTOKOLL_ABSOLUTER_PFAD", ROOTDIR ."" .WARTUNGSPROTOKOLL_RELATIVER_PFAD);

define('MWST_SATZ', 0.19);
define('STARTRECHNUNGSNUMMER', 1000);

define('SESSION_DEFAULT_EXPIRE', 1800); // halbe stunde

// Der Salt wird zum Speichern der Passwort Hashes in der Datenbank verwendet und mach den Hash etwas sicherer.
// define('PASSWORD_SALT', 'abc');


// Fehlerbehandlung
ini_set('display_errors', 1); // Bei HTTP-500 Fehlern + try/catch
define("ERROR_REPORTING_LVL", E_ALL); // also used in class.DB.php
error_reporting(ERROR_REPORTING_LVL);

include_once ROOTDIR . 'conf/classes.inc.php';

include_once ROOTDIR . 'src/core/error/class.ExceptionHandler.php';
include_once ROOTDIR . 'src/core/error/class.ErrorHandler.php';
set_error_handler("ErrorHandler::handle");
set_exception_handler("ExceptionHandler::handle");

// Klassen
$sessionHandler = new OrgelbankSessionHandler();
session_set_save_handler($sessionHandler, true);

// Logging
// Log::setLogger(new DoNothingLogger());
Log::setLogger(new EchoLogger());

// Globale Funktionen
include_once ROOTDIR . 'conf/functions.inc.php';

