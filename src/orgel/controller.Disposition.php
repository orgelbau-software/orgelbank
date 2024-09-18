<?php

class DispositionController
{

    public static function speichereReihenfolge()
    {
        if (! isset($_GET['oid']))
            return;
        
        $oOrgel = new Orgel($_GET['oid']);
        
        foreach ($_POST as $key => $val) {
            $oRegister = new Register($key);
            $oRegister->setReihenfolge($val);
            $oRegister->speichern(false);
        }
        
        $htmlRedirect = new HTMLRedirect("Reihenfolge gespeichert.", "index.php?page=4&do=61&oid=" . $oOrgel->getID(), 1);
        $htmlRedirect->anzeigen();
    }

    public static function bearbeiteDisposition()
    {
        $c = new DispositionBearbeitenAction();
        
        $boValid = false;
        if ($_POST) {
            $boValid = $c->validatePost();
        } else {
            $boValid = $c->validateGet();
        }
        
        if ($boValid == true) {
            $c->prepare();
            $c->executeBearbeiteDisposition();
        } else {
            echo "TODO: Es ist ein Fehler aufgetreten";
        }
    }

    public static function ajaxSortiereDisposition()
    {
        $orderPIDs = $_GET['order'];
        $orderPIDs = explode(",", $orderPIDs);
        $currentSortNumber = 0;
        $retVal = array();
        foreach ($orderPIDs as $currentDispositionsID) {
            if (is_numeric($currentDispositionsID)) {
                DispositionsUtilities::updateDispositionsOrder($currentDispositionsID, $currentSortNumber ++);
                $retVal[$currentSortNumber] = $currentDispositionsID;
            }
        }
        
        return array(
            "result" => "ok",
            "order",
            $retVal
        );
    }

    
}
?>
