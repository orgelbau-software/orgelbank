<?php

class DispositionBearbeitenAction
{

    private $oOrgel = null;

    private $iManualID = 0;

    private $oRegister = null;

    private $actionMethod = "undefined";

    public function validatePost()
    {
        if (isset($_POST['oid']) == false) {
            return false;
        }
        return true;
    }

    public function validateGet()
    {
        if (isset($_GET['oid']) == false) {
            return false;
        }
        return true;
    }

    public function prepare()
    {
        $this->oOrgel = (isset($_GET['oid']) ? new Orgel($_GET['oid']) : new Orgel($_POST['oid']));
        $this->determineManual();
        $this->determineRegister();
    }

    private function determineManual()
    {
        $this->iManualID = 1;
        if (isset($_POST['manual'])) {
            $this->iManualID = $_POST['manual'];
        } else {
            $tmp = RegisterUtilities::getLetztesEingepflegtesRegister($this->oOrgel->getID());
            if (null != $tmp) {
                $this->iManualID = $tmp->getManual();
            }
        }
    }

    private function determineRegister()
    {
        $tmp = RegisterUtilities::getLetztesEingepflegtesRegister($this->oOrgel->getID());
        if (isset($_GET['did'])) {
            $this->oRegister = new Register($_GET['did']);
        } elseif (isset($_GET['action'], $_GET['didbase']) && $_GET['action'] == "copy") {
            $copyFrom = new Register(intval($_GET['didbase']));
            $this->oRegister = new Register();
            $this->oRegister->setManual($copyFrom->getManual());
            $this->oRegister->setFuss($copyFrom->getFuss());
            $this->oRegister->setName($copyFrom->getName());
            $this->oRegister->setManual($tmp->getManual());
            $this->oRegister->setTyp($tmp->getTyp());
        } elseif ($_POST && isset($_POST['did']) && $_POST['did'] > 0) {
            $this->oRegister = new Register(intval($_POST['did']));
        } else {
            $this->oRegister = new Register();
            $this->oRegister->setManual($tmp->getManual());
        }
    }

    public function executeBearbeiteDisposition()
    {
        $tplDisposition = new Template("disposition_verwaltung.tpl");
        $tplManual = new Template("disposition_manual.tpl");
        $tplRegister = new BufferedTemplate("disposition_ds.tpl");
        
        // POST Verarbeitung
        if ($_POST) {
            if ($_POST['action'] == "speichern" && trim($_POST['register']) != "") {
                $this->oRegister->setOrgelID($this->oOrgel->getID());
                $this->oRegister->setManual($this->iManualID);
                $this->oRegister->setName($_POST['register']);
                $this->oRegister->setFuss($_POST['fuss']);
                $this->oRegister->setTyp($_POST['typ']);
                // $this->oRegister->setReihenfolge($_POST['position']);
                $this->oRegister->speichern(false);
                
                $htmlStatus = new HTMLStatus("Register wurde gespeichert.", 2);
                
                $this->oOrgel->setRegisterAnzahl(RegisterUtilities::getRegisterAnzahl($this->oOrgel->getID()));
                $this->oOrgel->speichern();
            } elseif ($_POST['action'] == "loeschen") {
                if (RegisterUtilities::exists($_POST['did'])) {
                    $this->oRegister = new Register($_POST['did']);
                    $htmlStatus = new HTMLStatus("Register wurde gel&ouml;scht.", 4);
                    $this->oRegister->loeschen();
                    
                    $this->oOrgel->setRegisterAnzahl(RegisterUtilities::getRegisterAnzahl($this->oOrgel->getID()));
                    $this->oOrgel->speichern();
                } else {
                    $htmlStatus = new HTMLStatus("Register wurde bereits gel&ouml;scht.", 1);
                }
            } else {
                $htmlStatus = new HTMLStatus("Ung&uuml;ltige Eingabe. Bitte Registerbezeichnung eingeben.", 1);
                $this->oRegister->setManual($this->iManualID);
            }
            
            $this->oRegister = new Register();
            $tmp = RegisterUtilities::getLetztesEingepflegtesRegister($this->oOrgel->getID());
            $this->oRegister->setManual($tmp->getManual());
            $tplDisposition->replace("HTMLStatus", $htmlStatus->getOutput());
        }
        
        // Template füllen
        $tplDisposition->replace("OID", $this->oOrgel->getID());
        $tplDisposition->replace("DID", $this->oRegister->getID());
        $tplDisposition->replace("Register", $this->oRegister->getName());
        
        $tplDisposition->replace("HTMLStatus", "");
        $tplDisposition->replace("Position", RegisterUtilities::getNaechsteRegisterPosition($this->oOrgel->getID(), $this->iManualID));
        
        if (! isset($_GET['action'])) {
            $tplDisposition->replace("Action", "speichern");
            $tplDisposition->replace("Submit", "Neu eintragen");
            $tplDisposition->replace("ButtonClass", "saveButton");
        } elseif ($_GET['action'] == "copy") {
            $tplDisposition->replace("Action", "speichern");
            $tplDisposition->replace("Submit", "&Uuml;bernehmen");
            $tplDisposition->replace("ButtonClass", "saveButton");
        } elseif ($_GET['action'] == "edit") {
            $tplDisposition->replace("Action", "speichern");
            $tplDisposition->replace("Submit", "Eintrag &auml;ndern");
            $tplDisposition->replace("ButtonClass", "editButton");
        } elseif ($_GET['action'] == "delete") {
            $tplDisposition->replace("Action", "loeschen");
            $tplDisposition->replace("Submit", "Eintrag l&ouml;schen");
            $tplDisposition->replace("ButtonClass", "deleteButton");
        }
        
        // Manuale ausgeben
        $c = OrgelUtilities::getOrgelManuale($this->oOrgel);
        $htmlSelect = new HTMLSelectForArray($c, $this->oRegister->getManual());
        $tplDisposition->replace("Manuale", $htmlSelect->getOutput());
        
        if($this->oRegister->getID() == -1) {
            $ausgewaehlteFussGroesse = 8; // Standard bei neuen Registern
        } else {
            $ausgewaehlteFussGroesse = $this->oRegister->getFuss();
        }
        
        // Register Typ / Transmission
        $htmlSelect = new HTMLSelectForArray(Constant::getDispositionTyp(), $this->oRegister->getTyp() );
        $tplDisposition->replace("Typ", $htmlSelect->getOutput());
        
        // Register Groessen
        $c = RegisterUtilities::getRegisterGroessen();
        $htmlSelect = new HTMLSelectForKey($c, "getBezeichnung", "getBezeichnungsText", $ausgewaehlteFussGroesse);
        $tplDisposition->replace("Fuss", $htmlSelect->getOutput());
        
        // Disposition aus der Datenbank lesen
        $c = RegisterUtilities::ladeOrgelRegister($this->oOrgel->getID(), " ORDER BY m_id, d_reihenfolge");
        $oldmanual = "";
        
        foreach ($c as $this->oRegister) {
            if ($this->oRegister->getManual() != 6) {
                $manualTxt = $this->oRegister->getManual() . ". Manual";
            } else {
                $manualTxt = "Pedal";
            }
            
            if ($this->oRegister->getManual() != $oldmanual) {
                $tplManual->replace("Manual", $manualTxt);
                $tplRegister->addToBuffer($tplManual);
                $tplManual->reset();
            }
            
            $name = $this->oRegister->getName();
            if($this->oRegister->getTyp() == 2) {
                $name .= " (T)";
            } elseif($this->oRegister->getTyp() == 3) {
                $name .= " (E)";
            }
            $tplRegister->replace("Name", $name."");
            $tplRegister->replace("Fuss", $this->oRegister->getFuss());
            $tplRegister->replace("MID", $this->oRegister->getManual());
            $tplRegister->replace("DID", $this->oRegister->getID());
            $tplRegister->replace("Reihenfolge", $this->oRegister->getReihenfolge());
            $tplRegister->replace("OID", $this->oOrgel->getID());
            $tplRegister->next();
            
            $oldmanual = $this->oRegister->getManual();
        }
        
        $tplDisposition->replace("Disposition", $tplRegister->getOutput());
        
        if ($this->oOrgel->getAnzahlManuale() == 0)
            $tplDisposition->replace("disabled", "disabled");
        $tplDisposition->replace("disabled", "");
        
        // Disposistions TOP Einträge zur Auswahl
        $c = RegisterUtilities::getTOPRegister(ConstantLoader::getDefaultTOPRegister());
        $tplTOPRegister1 = new BufferedTemplate("disposition_top_ds.tpl");
        $tplTOPRegister2 = new BufferedTemplate("disposition_top_ds.tpl");
        $tplTOPRegister3 = new BufferedTemplate("disposition_top_ds.tpl");
        $tplTOPRegisterCurrent = $tplTOPRegister1;
        $iCounter = 0;
        
        $eintraegeProSpalte = ConstantLoader::getDefaultTOPRegister() / 3;
        
        foreach ($c as $topRegister) {
            $iCounter ++;
            $tplTOPRegisterCurrent->replace("Name", $topRegister->getName());
            $tplTOPRegisterCurrent->replace("Fuss", $topRegister->getFuss());
            $tplTOPRegisterCurrent->replace("OID", $this->oOrgel->getID());
            $tplTOPRegisterCurrent->replace("DID", $topRegister->getID());
            $tplTOPRegisterCurrent->next();
            
            if ($iCounter < $eintraegeProSpalte) {
                $tplTOPRegisterCurrent = $tplTOPRegister1;
            } elseif ($iCounter < (2*$eintraegeProSpalte)) {
                $tplTOPRegisterCurrent = $tplTOPRegister2;
            } else {
                $tplTOPRegisterCurrent = $tplTOPRegister3;
            }
        }
        
        $tplDisposition->replace("DispositionTOP1", $tplTOPRegister1->getOutput());
        $tplDisposition->replace("DispositionTOP2", $tplTOPRegister2->getOutput());
        $tplDisposition->replace("DispositionTOP3", $tplTOPRegister3->getOutput());
        
        $tplDisposition->anzeigen();
    }
}
?>