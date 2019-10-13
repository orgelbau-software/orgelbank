<?php
include_once '../../../conf/config.inc.php';
$db = DB::getInstance();
$db->connect();

/*
 *
 * Addressbook/CardDAV server example
 *
 * This server features CardDAV support
 */

// settings
// date_default_timezone_set('Canada/Eastern');

// Make sure this setting is turned on and reflect the root url for your WebDAV server.
// This can be for example the root / or a complete path to your server script
$baseUri = '/orgelbank/src/services/addressbook/orgelbank.php';

/* Database */
// $pdo = new PDO('sqlite:data/db.sqlite');
$pdo = new PDO('mysql:dbname=sabredav;host=127.0.0.1', 'sabredav', 'sabredav');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Mapping PHP errors to exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

// Autoloader
require_once ROOTDIR . 'lib/sabre-dav/vendor/autoload.php';

ConstantLoader::performAutoload();

require_once ROOTDIR . 'src/services/addressbook/class.OrgelbankCalDavAuthBackend.php';
require_once ROOTDIR . 'src/services/addressbook/class.OrgelbankPrincipalBackend.php';
require_once ROOTDIR . 'src/services/addressbook/class.OrgelbankAddressbookBackend.php';
require_once ROOTDIR . 'src/services/addressbook/class.DAACLLoggerPlugin.php';

require_once ROOTDIR . 'lib/vcard/src/VCard.php';
require_once ROOTDIR . 'lib/vcard/src/VCardMediaException.php';
require_once ROOTDIR . 'lib/vcard/src/VCardParser.php';
require_once ROOTDIR . 'lib/vcard/src/Exception.php';
require_once ROOTDIR . 'lib/vcard/vendor/autoload.php';
require_once ROOTDIR . 'src/services/addressbook/class.AnsprechpartnerToVCardConverter.php';

require_once ROOTDIR . 'src/services/addressbook/FileLogger.php';

// Backends
$authBackend = new OrgelbankCalDavAuthBackend();

// $principalBackend = new Sabre\DAVACL\PrincipalBackend\PDO($pdo);
$principalBackend = new OrgelbankPrincipalBackend();
$carddavBackend = new Sabre\CardDAV\Backend\PDO($pdo);
// $carddavBackend = new OrgelbankAddressbookBackend();

$addressbook = new Sabre\CardDAV\AddressBook($carddavBackend, array(
    "id" => 3,
    "uri" => "contacts",
    "principaluri" => $authBackend->getCurrentUser()
));

$server = new Sabre\DAV\Server($addressbook);
$server->setBaseUri($baseUri);

$aclPlugin = new DAACLLoggerPlugin();
// $aclPlugin->adminPrincipals = array(
// "principals/foo",
// "foo"
// );

// Plugins
$server->addPlugin(new Sabre\DAV\Auth\Plugin($authBackend, 'SabreDAV'));
$server->addPlugin(new Sabre\DAV\Browser\Plugin());
// $server->addPlugin(new Sabre\CalDAV\Plugin());
$server->addPlugin(new Sabre\CardDAV\Plugin());
$server->addPlugin($aclPlugin);
$server->addPlugin(new Sabre\DAV\Sync\Plugin());

// And off we go!
$server->exec();

$db->disconnect();
