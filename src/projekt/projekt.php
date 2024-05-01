<?php
include "../../conf/config.inc.php";

DB::getInstance()->connect();

session_start();

$user = new WebBenutzer();
if ($user->validateSession() == false) {
    die("keine gueltige session");
}

if (isset($_GET['action'], $_GET['request']) && $_GET['action'] == "ajax") {
        
    if (isset($_GET['request'], $_GET['a'], $_GET['p']) && $_GET['request'] == "unteraufgaben") {
        
        $ha = AufgabeUtilities::loadChildrenAufgaben(intval($_GET['a']));
        $projektAufgabe = new ProjektAufgabe($_GET['p'], $_GET['a']);
        print_r($projektAufgabe);
        $tpl = new BufferedTemplate("projekt_details_uaufgabe_ds.tpl", "CSS", "td3", "td4");
        
        $lohnKosten = ZeiterfassungUtilities::getProjektAufgabeLohnkosten($_GET['p'], $_GET['a'], true);
        $p = new Projekt($_GET['p']);
        $gesLohn = 0;
        
        // Aufgaben / Kosten Übersicht
        foreach ($ha as $haufgabe) {
            $tmpBezeichnung = $haufgabe->getBezeichnung();
            if ($haufgabe->getSelected() == "false")
                $tmpBezeichnung .= "*";
            $tpl->replace("Aufgabe", $tmpBezeichnung);
            
            $tmpLohnKosten = $tmpStunden = 0;
            if (isset($lohnKosten[$haufgabe->getID()])) {
                $tmpLohnKosten = $lohnKosten[$haufgabe->getID()]['lohnkosten'];
                $tmpStunden = $lohnKosten[$haufgabe->getID()]['stunden'];
                unset($lohnKosten[$haufgabe->getID()]);
            }
            
            $gesLohn += $tmpLohnKosten;
            
            $tpl->replace("ProjektID", $p->getID());
            $tpl->replace("AufgabeID", $haufgabe->getID());
            $tpl->replace("ParentID", $_GET['a']);
            if($projektAufgabe->getSollStunden() > 0) {
                $tpl->replace("Stunden", $tmpStunden . "/" .$projektAufgabe->getSollStunden());
            } else {
                $tpl->replace("Stunden", $tmpStunden);
            }
            $tpl->replace("Kosten", WaehrungUtil::formatDoubleToWaehrung($tmpLohnKosten));
            $tpl->next();
        }
        
        // Uebrige Lohnkosten zu bereits geloeschten Aufgaben beachten
        foreach ($lohnKosten as $key => $val) {
            $aufgabe = new Aufgabe($key);
            $tpl->replace("ProjektID", $p->getID());
            $tpl->replace("Aufgabe", $aufgabe->getBezeichnung());
            $tpl->replace("AufgabeID", $aufgabe->getID());
            $tpl->replace("ParentID", $_GET['a']);
            $tpl->replace("Stunden", $val['stunden']);
            $tpl->replace("Kosten", WaehrungUtil::formatDoubleToWaehrung($val['lohnkosten']));
            $tpl->next();
        }
        
        // fuer AJAX Übertragung muss UTF8 kodiert werden
        echo $tpl->getOutput();
    } elseif (isset($_GET['request'], $_GET['a'], $_GET['p']) && $_GET['request'] == "ams") {
        // AufgabeMitarbeiterstunden
        $s = ZeiterfassungUtilities::getProjektMitarbeiterStundenSumme($_GET['p'], $_GET['a'], true);
        $tpl = new BufferedTemplate("projekt_details_uaufgabe_ams_ds.tpl", "CSS", "td1", "td2");
        
        $p = new Projekt($_GET['p']);
        $a = new Aufgabe($_GET['a']);
        $gesLohn = 0;
        
        // Aufgaben / Kosten Übersicht
        foreach ($s as $benutzername => $data) {
            $tpl->replace("Benutzername", htmlspecialchars(mb_convert_encoding($benutzername, 'UTF-8', 'ISO-8859-1')));
            $tpl->replace("ProjektID", $p->getID());
            $tpl->replace("ParentID", $_GET['a']);
            $tpl->replace("ParentParentID", $a->getParentID());
            $tpl->replace("Stunden", $data['stunden']);
            $tpl->replace("Kosten", WaehrungUtil::formatDoubleToWaehrung($data['lohnkosten']));
            $tpl->next();
        }
        
        // fuer AJAX Übertragung muss UTF8 kodiert werden
        $out = $tpl->getOutput();
        echo mb_convert_encoding($out == null ? "" : $out, 'UTF-8', 'ISO-8859-1');
    } elseif (isset($_GET['request'], $_GET['date']) && $_GET['request'] == "mitarbeiterstunden") {
        $tpl = ProjektController::ajaxGetMitarbeiterWochenStunden($_GET['date']);
        echo mb_convert_encoding($tpl->getOutput(), 'UTF-8', 'ISO-8859-1');
    } elseif (isset($_GET['request'], $_GET['order']) && $_GET['request'] == "projectsort") {
        // echo "projectorder". $_GET['order'];
        header('Content-Type: application/json');
        echo json_encode(ProjektController::ajaxSortiereProjectliste());
    } elseif (isset($_GET['request'], $_GET['order']) && $_GET['request'] == "mitarbeitersort") {
        // echo "projectorder". $_GET['order'];
        header('Content-Type: application/json');
        echo json_encode(ProjektController::ajaxSortiereMitarbeiter());
    } elseif (isset($_GET['request'], $_GET['order'], $_GET['pid']) && $_GET['request'] == "projektaufgabensortierung") {
        // echo "projectorder". $_GET['order'];
        header('Content-Type: application/json');
        echo json_encode(ProjektController::ajaxSortiereProjektAufgaben());
    } else {
        echo "unbekannt";
    }
} else if(isset($_GET['action']) && $_GET['action'] == "stundenzettel") {    
    ProjektController::druckeStundenzettel();
} else {
    echo "unbekannt";
}


?>