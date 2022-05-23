<?php
include_once '../../conf/config.inc.php';
include_once 'class.OrgelbankBasisPDF.php';
include_once 'class.WartungsbogenPDF.php';
include_once 'class.OrgelbankWartungsbogenPDF.php';
include_once 'class.DeckblattPDF.php';
include_once 'class.OrgelbankDeckblattPDF.php';;

// Kunden spezifisch
include_once 'class.GraserOrgelbankDeckblattPDF.php';;

$db = DB::getInstance();
$db->connect();

session_start();
$user = new WebBenutzer();
if ($user->validateSession() == false) {
    die("keine gueltige session");
}

ConstantLoader::performAutoload();

if(isset($_POST['submit'])) {
    $action = strtolower($_POST['submit']);
} else if(isset($_GET['action'])) {
    $action = strtolower($_GET['action']);
} else {
    $action = "unbekannt";
}


if(strpos($action, "deckbl") === 0) {
    $pdf = new GraserOrgelbankDeckblattPDF();

    if (isset($_POST['orgelliste'])) {
        SeitenStatistik::count("deckblatt.php?oid=X,Y,Z", "PDF::printManyOrgel");
        foreach ($_POST as $key => $value) {
            if (is_numeric($value)) {
                $pdf->addOrgel(new Orgel($value));
            }
        }
        $pdf->Output("I", "Deckblatt.pdf");
        $db->disconnect();
    } elseif (isset($_GET['oid'])) {
        SeitenStatistik::count("deckblatt.php?oid=X", "PDF::printSingleOrgel");
        $pdf->addOrgel(new Orgel($_GET['oid']));
        $pdf->Output("I", "Deckblatt.pdf");
        $db->disconnect();
    } else {
        die("Keine Orgel ID uebergeben fuer Deckblatt.");
    }
    
    
    
} else {
    $pdf = new OrgelbankWartungsbogenPDF();
    
    if (isset($_POST['orgelliste'])) {
        SeitenStatistik::count("pdf.php?oid=X,Y,Z", "PDF::printManyOrgel");
        foreach ($_POST as $key => $value) {
            if (is_numeric($value)) {
                $pdf->addOrgel(new Orgel($value));
            }
        }
        $pdf->Output("I", "Wartungsunterlagen.pdf");
        $db->disconnect();
    } elseif (isset($_GET['oid'])) {
        SeitenStatistik::count("pdf.php?oid=X", "PDF::printSingleOrgel");
        $pdf->addOrgel(new Orgel($_GET['oid']));
        $pdf->Output("I", "Wartungsunterlagen.pdf");
        $db->disconnect();
    } else {
        die("Keine Orgel ID uebergeben fuer Wartungsbogen.");
    }
    
}

// PDF ausgeben

$db->disconnect();
?>