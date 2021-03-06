<?php



/*

Addressbook/CardDAV server example

This server features CardDAV support

*/

// settings
//date_default_timezone_set('Canada/Eastern');

// Make sure this setting is turned on and reflect the root url for your WebDAV server.
// This can be for example the root / or a complete path to your server script
$baseUri = '/sabre-dav/addressbookserver.php';

/* Database */
//$pdo = new PDO('sqlite:data/db.sqlite');
$pdo = new PDO('mysql:dbname=sabredav;host=127.0.0.1', 'sabredav', 'sabredav');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

//Mapping PHP errors to exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

// Autoloader
require_once 'vendor/autoload.php';

class StephansBackendInterface implements Sabre\DAV\Auth\Backend\BackendInterface {
	 function authenticate(\Sabre\DAV\Server $server,$realm) {
		 return true;
	 }

    /**
     * Returns information about the currently logged in username.
     *
     * If nobody is currently logged in, this method should return null.
     *
     * @return string|null
     */
    function getCurrentUser() {
		return "admin";
	}
}

// Backends
$authBackend      = new Sabre\DAV\Auth\Backend\PDO($pdo);
$authBackend      = new StephansBackendInterface();


$principalBackend = new Sabre\DAVACL\PrincipalBackend\PDO($pdo);
$carddavBackend   = new Sabre\CardDAV\Backend\PDO($pdo);
//$caldavBackend    = new Sabre\CalDAV\Backend\PDO($pdo);

// Setting up the directory tree //
$nodes = [
    new Sabre\DAVACL\PrincipalCollection($principalBackend),
//    new Sabre\CalDAV\CalendarRoot($authBackend, $caldavBackend),
    new Sabre\CardDAV\AddressBookRoot($principalBackend, $carddavBackend),
];

// The object tree needs in turn to be passed to the server class
$server = new Sabre\DAV\Server($nodes);
$server->setBaseUri($baseUri);

// Plugins
$server->addPlugin(new Sabre\DAV\Auth\Plugin($authBackend,'SabreDAV'));
$server->addPlugin(new Sabre\DAV\Browser\Plugin());
//$server->addPlugin(new Sabre\CalDAV\Plugin());
$server->addPlugin(new Sabre\CardDAV\Plugin());
$server->addPlugin(new Sabre\DAVACL\Plugin());
$server->addPlugin(new Sabre\DAV\Sync\Plugin());

// And off we go!
$server->exec();



	