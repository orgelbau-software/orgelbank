<?php

// Benchmarking
$renderStart = microtime(true);
// Config einbinden
include "conf/config.inc.php";

session_start();

// Session überprüfen
ConstantLoader::performAutoload();
$tplKopf = new Template("indexkopf_notloggedin.tpl");
$tplFuss = new Template("indexfuss_notloggedin.tpl");
$webUser = null;

if(!defined("ORGELBANK_API_KEY") || ConstantLoader::getOrgelbankAPIKey() != ORGELBANK_API_KEY) {
    die("Der API Key stimmt nicht ueberein: " .(defined("ORGELBANK_API_KEY") ? ORGELBANK_API_KEY : "API Konstante nicht gesetzt."));
}

if($_POST && isset($_POST['loginmodule'])) {
  $_POST['benutzername'] = filter_var($_POST['benutzername'], FILTER_SANITIZE_STRING);
  $_SESSION['user']['benutzername'] = $_POST['benutzername'];
  $_SESSION['user']['passwort'] = PasswordUtility::encrypt($_POST['passwort']);
}

if(isset($_SESSION['user']['benutzername'], $_SESSION['user']['passwort'])) {
  $webUser = new WebBenutzer();
  $webUser->setBenutzername($_SESSION['user']['benutzername']);
  $webUser->setPasswort($_SESSION['user']['passwort']);
}

if($webUser != null && $webUser->login() && ! $webUser->isLoginExpired()) {
  $tplKopf = new Template("indexkopf.tpl");
  $tplFuss = new Template("indexfuss.tpl");
  $tplSubMenu = null;
  $tplMenu = null;
  
  // Kopfzeile
  $date = new Date();
  $tplKopf->replace("SiteTitle", ConstantLoader::getSiteTitle());
  $tplKopf->replace("Datum", $date->getMonthDate());
  $tplKopf->replace("Uhrzeit", $date->getTime());
  $tplKopf->replace("Benutzername", $webUser->getBenutzername());
  $tplKopf->replace("AutoLogoutSekunden", ConstantLoader::getBenutzerAutomatischerLogoutInSekunden());
  
  
  if($webUser->isMonteur()) {
    $cssMenuAktiv = "";
    $currentPageId = 1;
    if(isset($_GET['page'])) {
      $currentPageId = intval($_GET['page']);
    }
    switch($currentPageId) {
      case 2:
        $cssMenuAktiv = "Orgel";
        $tplSubMenu = new Template("orgel_menu.tpl");
        $tplJS = new Template("orgel_js.tpl");
        $tplKopf->replace("JavaScript", $tplJS->forceOutput());
        break;
      case 3:
        $cssMenuAktiv = "Ansprechpartner";
        $tplSubMenu = new Template("ansprechpartner_menu.tpl");
        break;
      case 4:
        $cssMenuAktiv = "Orgel";
        $tplSubMenu = new Template("disposition_menu.tpl");
        $tplJS = new Template("disposition_js.tpl");
        $tplKopf->replace("JavaScript", $tplJS->forceOutput());
        if(isset($_GET['oid']))
          $tplSubMenu->replace("OID", $_GET['oid']);
        break;
      case 5:
        $cssMenuAktiv = "Rechnung";
        $tplSubMenu = new Template("rechnung_menu.tpl");
        $tplJS = new Template("rechnung_js.tpl");
        $tplJS->replace("MwStSatz", MWST_SATZ);
        RechnungUtilities::renderRechnungsJavaScripts($tplJS);
        $tplKopf->replace("JavaScript", $tplJS->getOutput());
        break;
      case 6:
        $cssMenuAktiv = "Projekt";
        $tplSubMenu = new Template("projekt_menu.tpl");
        $tplJS = new Template("projekt_js.tpl");
        $tplJS->replace("kmPauschale", ConstantLoader::getKilometerpauschale());
        $tplKopf->replace("JavaScript", $tplJS->getOutput());
        break;
      case 7:
        $cssMenuAktiv = "Einstellung";
        $tplSubMenu = new Template("einstellung_menu.tpl");
        break;
      case 8:
        $cssMenuAktiv = "Benutzer";
        $tplSubMenu = new Template("benutzer_menu.tpl");
        break;
      default:
        $cssMenuAktiv = "Gemeinde";
        $tplSubMenu = new Template("gemeinde_menu.tpl");
        if(isset($_GET['do']) && ($_GET['do'] == 2 || $_GET['do'] == 4)) {
          
          $tplJS = new Template("gemeinde_js.tpl");
          $tplJS->replace("GoogleAPIKey", GOOGLE_MAPS_API_KEY);
          $gemeindeJS = $tplJS->getOutput();
          
          $tplJS = GemeindeUtilities::generateGemeindeCopyJS();
          $tplKopf->replace("BodyTags", ConstantLoader::getGoogleHTMLBodyProperty());
          $gemeindeJS .= $tplJS->getOutput();
          
          $tplKopf->replace("JavaScript", $gemeindeJS);
        }
        break;
    }
    $tplMenu = new Template("index_menu_admin.tpl");
    $cssMenu = array("Gemeinde", "Orgel", "Ansprechpartner", "Rechnung", "Projekt", "Einstellung", "Benutzer");
    $tplMenu->replace($cssMenuAktiv, "aktiv");
    foreach($cssMenu as $item) {
      $tplMenu->replace($item, "inaktiv");
    }
  } else {
    $tplMenu = new Template("index_menu_mitarbeiter.tpl");
    $tplSubMenu = new Template("benutzer_menu.tpl");
  }
  $tplKopf->replace("BodyTags", "");
  $tplKopf->replace("InstanceUrl", INSTANCE_URL);
  $tplKopf->replace("Menu", $tplMenu->forceOutput());
  
  if($tplSubMenu != null)
    $tplKopf->replace("SubMenu", $tplSubMenu->forceOutput());
  // Head ausgeben
  echo $tplKopf->forceOutput();
  // Aufgerufene Seite wird hier eingebunden
  include_once 'src/controller/controller.Main.php';
} else {
  $tplStatus = null;
  if($_POST) {
    $errorMsg = "Bitte &uuml;berpr&uuml;fen Sie ihre Daten.";
    if($webUser != null)
      $errorMsg = "<br/>" . $webUser->getErrorMessage();
    $tplStatus = new HTMLStatus("Anmeldung fehlgeschlagen. " . $errorMsg, 1);
  }
  
  if($webUser != null && $webUser->isLoginExpired())
    $tplStatus = new HTMLStatus("Sie wurden automatisch ausgeloggt, da zu lange keine Eingabe vorgenommen wurde. Bitte erneut anmelden.", 4);
  
  if($tplStatus != null)
    $tplKopf->replace("Statusmeldung", $tplStatus->getOutput());
  $tplKopf->replace("Statusmeldung", "");
  $tplKopf->replace("SiteTitle", ConstantLoader::getSiteTitle());
  $tplKopf->anzeigen();
}
// Footer ausgeben;
$tplFuss->replace("AdminHoverText", ConstantLoader::getAdminNachrichtenHoverText());
$tplFuss->replace("PostValue", print_r($_POST, true));
$tplFuss->replace("GetValue", $_SERVER['REQUEST_URI']);

$renderEnde = microtime(true);

$tplFuss->replace("NumQuery", DB::$num_queries);
echo $tplFuss->forceOutput();

// Tracking, unbedingt vor dem setzen der LastActionTime
BenutzerVerlaufTracker::track();

// Sessionzeit speichern
$_SESSION['request']['lastaction'] = time();

?>