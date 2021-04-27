<?php
include_once '../../conf/config.inc.php';
// include_once '../../lib/fpdf.php';
// include_once '../../lib/fpdfbookmark.php';
// include_once '../../lib/fpdf/fpdf.php';
// include_once '../../lib/fpdf/fpdfbookmark.php';
include_once '../../lib/tFPDF/tfpdf.php';
include_once '../../lib/tFPDF/tfpdfbookmark.php';
include_once 'class.WartungsbogenPDF.php';
include_once 'class.OrgelbankWartungsbogenPDF.php';
include_once 'class.DeckblattPDF.php';
include_once 'class.OrgelbankDeckblattPDF.php';;

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
    echo "Fehler";
}


if(strpos($action, "deckbl") === 0) {
    $pdf = new OrgelbankDeckblattPDF();

    if (isset($_POST['orgelliste'])) {
        SeitenStatistik::count("deckblatt.php?oid=X,Y,Z", "PDF::printManyOrgel");
        foreach ($_POST as $key => $value) {
            if (is_numeric($value)) {
                $pdf->addOrgel(new Orgel($value));
            }
        }
        $pdf->Output("Deckblatt.pdf", "I");
        $db->disconnect();
    } elseif (isset($_GET['oid'])) {
        SeitenStatistik::count("deckblatt.php?oid=X", "PDF::printSingleOrgel");
        $pdf->addOrgel(new Orgel($_GET['oid']));
        $pdf->Output("Deckblatt.pdf", "I");
        $db->disconnect();
    } else {
        echo "Keine Orgel ID uebergeben.";
    }
    
    $pdf->Output("Deckblatt.pdf", "I");
    
} else {
    $pdf = new OrgelbankWartungsbogenPDF();
    
    if (isset($_POST['orgelliste'])) {
        SeitenStatistik::count("pdf.php?oid=X,Y,Z", "PDF::printManyOrgel");
        foreach ($_POST as $key => $value) {
            if (is_numeric($value)) {
                $pdf->addOrgel(new Orgel($value));
            }
        }
        $pdf->Output("Wartungsunterlagen.pdf", "I");
        $db->disconnect();
    } elseif (isset($_GET['oid'])) {
        SeitenStatistik::count("pdf.php?oid=X", "PDF::printSingleOrgel");
        $pdf->addOrgel(new Orgel($_GET['oid']));
        $pdf->Output("Wartungsunterlagen.pdf", "I");
        $db->disconnect();
    } else {
        echo "Keine Orgel ID uebergeben.";
    }
    $pdf->Output("Wartungsunterlagen.pdf", "I");
}

// PDF ausgeben

$db->disconnect();
?>