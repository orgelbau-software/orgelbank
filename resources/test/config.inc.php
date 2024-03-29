<?php
//config.php
date_default_timezone_set("Europe/Berlin");
ob_start(); // FirePHP


// Providerspezifische Zugangsdaten laden
include_once 'example.conf.php';

// Konstanten
define('TRACEENABLED', false);

define('SUPPORT_MAIL_ADDR', "stephan@watermeyer.info");

define("ORGELBILD_BILD_PFAD", ROOTDIR . "store/orgelpics/");
define("ORGELBILD_THUMB_PFAD", ORGELBILD_BILD_PFAD . "thumbs/");
define('RECHNUNGDIR', ROOTDIR . 'store/rechnungen/');

define('MWST_SATZ', 0.19);
define('STARTRECHNUNGSNUMMER', 1000);

define('SESSION_DEFAULT_EXPIRE', 1800); // halbe stunde


// Fehlerbehandlung
define("ERROR_REPORTING_LVL", E_ALL); // also used in class.DB.php
error_reporting(E_ALL);

include_once ROOTDIR . 'conf/classes.inc.php';
include_once ROOTDIR . 'conf/pear.inc.php';

include_once ROOTDIR . 'src/core/error/class.ExceptionHandler.php';
include_once ROOTDIR . 'src/core/error/class.ErrorHandler.php';
set_error_handler("ErrorHandler::handle");
set_exception_handler("ExceptionHandler::handle");

// Klassen

session_set_save_handler(array('OrgelbankSessionHandler', 'open'), array('OrgelbankSessionHandler', 'close'), array('OrgelbankSessionHandler', 'read'), array('OrgelbankSessionHandler', 'write'), array('OrgelbankSessionHandler', 'destroy'), array('OrgelbankSessionHandler', 'gc'));

// Logging
// Log::setLogger(new FirePHPLogger());
//Log::setLogger(new EchoLogger());
Log::setLogger(new DoNothingLogger());

// Globale Funktionen
include_once ROOTDIR . 'conf/functions.inc.php';

?>