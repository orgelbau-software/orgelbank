<?php

class ProjektAufgabenVerwaltung implements GetRequestHandler, PostRequestHandler
{

    /**
     *
     * {@inheritdoc}
     *
     * @return bool
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return HTMLStatus
     */
    public function handleInvalidGet()
    {
        return new HTMLStatus("Alles ok");
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function prepareGet()
    {
        return;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return Template
     */
    public function executeGet()
    {
        $tpl = new Template("projekt_aufgabe_verwalten.tpl");
        $tplAufgDS = new BufferedTemplate("projekt_aufgabe_verwalten_ds.tpl");
        $tplAufgUADS = new BufferedTemplate("projekt_aufgabe_verwalten_uaufg_ds.tpl", "CSS", "td1", "td2");
        $tplUADS = new BufferedTemplate("projekt_aufgabe_verwalten_uaufgabe_ds.tpl", "CSS", "td1", "td2");
        $ProjektAufgabe = new Aufgabe();
        $htmlStatus = null;
        
        if ($_POST) {
            Utilities::escapePost();
            $htmlStatus = new HTMLStatus();
            $paID = 0;
            
            if ($_POST['paid'] != - 1)
                $paID = $_POST['paid'];
            
            $ProjektAufgabe = new Aufgabe($paID);
            
            if ($_POST['form'] == "hauptaufgabe") {
                if ($_POST['submit'] == "Ja, Aufgabe löschen" && isset($_POST['paid'])) {
                    $ProjektAufgabe->loeschen();
                    $ProjektAufgabe->speichern(false);
                    AufgabeUtilities::loescheUnteraufgaben($_POST['paid']);
                    $htmlStatus->setText("Projektaufgabe gel&ouml;scht");
                    $htmlStatus->setStatusclass(2);
                } else {
                    if (trim($_POST['bezeichnung']) != "") {
                        $ProjektAufgabe->setBeschreibung($_POST['beschreibung']);
                        $ProjektAufgabe->setBezeichnung($_POST['bezeichnung']);
                        $ProjektAufgabe->setChangeAt(0);
                        
                        if ($_POST['submit'] == "Speichern" && AufgabeUtilities::aufgabeExists($ProjektAufgabe->getBezeichnung())) {
                            $htmlStatus->setText("Aufgabe existiert oder existierte bereits.<br/> Soll sie wiederhergestellt werden? Wenden Sie sich an ihren Systembetreuer.");
                            $htmlStatus->setStatusclass(1);
                        } else {
                            $ProjektAufgabe->speichern(true);
                            $htmlStatus->setText("Aufgabe gespeichert.");
                            $htmlStatus->setStatusclass(2);
                        }
                        
                        $tpl->replace("UnteraufgabeSubmit", "Hinzufügen");
                        $tpl->replace("UnteraufgabeDisabled", "");
                        $tpl->replace("BenutzerDisabled", "");
                    } else {
                        $htmlStatus->setText("Aufgabenbezeichnung darf nicht leer sein!");
                        $htmlStatus->setStatusclass(1);
                    }
                }
            } elseif ($_POST['form'] == "unteraufgabe") {
                if ($_POST['submit'] == "Hinzufügen" || $_POST['submit'] == "Bearbeiten") {
                    $p = new Aufgabe();
                    if (isset($_POST['unteraufgabeid']))
                        $p = new Aufgabe(intval($_POST['unteraufgabeid']));
                    
                    $p->setBezeichnung($_POST['unteraufgabe_bez']);
                    $p->setParentID($_POST['paid']);
                    
                    if (AufgabeUtilities::aufgabeExists($p->getBezeichnung())) {
                        $htmlStatus->setText("Unteraufgabe existiert oder existierte bereits.<br/> Soll sie wiederhergestellt werden? Wenden Sie sich an ihren Systembetreuer.");
                        $htmlStatus->setStatusclass(1);
                        $p = new Aufgabe();
                    } elseif (trim($_POST['unteraufgabe_bez']) == "") {
                        $htmlStatus->setText("Bezeichnung muss gef&uuml;lt sein.");
                        $htmlStatus->setStatusclass(1);
                    } else {
                        $htmlStatus->setText("Unteraufgabe gespeichert");
                        $htmlStatus->setStatusclass(2);
                        $p->speichern(true);
                    }
                } elseif ($_POST['submit'] == "Ja, löschen" && isset($_POST['unteraufgabeid'])) {
                    $p = new Aufgabe(intval($_POST['unteraufgabeid']));
                    $p->loeschen();
                    $p->speichern(false);
                    
                    $htmlStatus->setText("Unteraufgabe gel&ouml;scht.");
                    $htmlStatus->setStatusclass(2);
                }
            } elseif ($_POST['form'] == "mitarbeiter") {
                AufgabeUtilities::resetAufgabeMitarbeiterZuordnung($ProjektAufgabe->getID());
                
                foreach ($_POST as $key => $val) {
                    if (substr($key, 0, 6) == "aktiv_") {
                        $s = substr($key, 6);
                        AufgabeUtilities::addMitarbeiterAufgabeFreischalten($ProjektAufgabe->getID(), $s);
                    }
                }
                
                $htmlStatus->setText("Mitarbeiterzuordnung ge&auml;ndert.");
                $htmlStatus->setStatusclass(2);
            }
        }
        
        // Beschriftungen Hauptaufgabe laden
        if (isset($_GET['a'], $_GET['paid'])) {
            $ProjektAufgabe = new Aufgabe($_GET['paid']);
            
            if ($_GET['a'] == "view") {
                $tpl->replace("Submit", "Speichern");
                $tpl->replace("BenutzerDisabled", "Disabled");
                $tpl->replace("UnteraufgabeDisabled", "Disabled");
            } elseif ($_GET['a'] == "edit") {
                $tpl->replace("Submit", "Bearbeiten");
                $tpl->replace("BenutzerDisabled", "");
                $tpl->replace("UnteraufgabeDisabled", "");
            } elseif ($_GET['a'] == "del") {
                $tpl->replace("Submit", "Ja, Aufgabe löschen");
                $tpl->replace("BenutzerDisabled", "Disabled");
                $tpl->replace("UnteraufgabeDisabled", "Disabled");
            }
        }
        
        // Beschriftungen der Unteraufgabe laden
        if (isset($_GET['ua'], $_GET['upaid'])) {
            $UnterProjektAufgabe = new Aufgabe(intval($_GET['upaid']));
            $tpl->replace("UPaID", $UnterProjektAufgabe->getID());
            
            if ($_GET['ua'] == "edit") {
                $tpl->replace("UnteraufgabeSubmit", "Bearbeiten");
                $tpl->replace("UnteraufgabeBezeichnung", $UnterProjektAufgabe->getBezeichnung());
            } elseif ($_GET['ua'] == "del") {
                $tpl->replace("UnteraufgabeSubmit", "Ja, löschen");
                $tpl->replace("UnteraufgabeBezeichnung", $UnterProjektAufgabe->getBezeichnung());
            }
        }
        
        // Projektaufgaben ausgeben
        $col = AufgabeUtilities::getHauptAufgaben();
        foreach ($col as $o) {
            $tplAufgDS->replace("Aufgabe", $o->getBezeichnung());
            $desc = strlen($o->getBeschreibung()) > 55 ? substr($o->getBeschreibung(), 0, 55) . "..." : $o->getBeschreibung();
            $tplAufgDS->replace("Beschreibung", $desc);
            $tplAufgDS->replace("PaID", $o->getID());
            $tplAufgDS->next();
            
            $cUAufg = AufgabeUtilities::loadChildrenAufgaben($o->getID());
            if ($cUAufg->getSize() > 0) {
                foreach ($cUAufg as $oUAufg) {
                    $tplAufgUADS->replace("Aufgabe", $oUAufg->getBezeichnung());
                    $tplAufgUADS->next();
                }
            } else {
                $tplAufgUADS->replace("Aufgabe", "Sie m&uuml;ssen Unteraufgaben eingeben um Zeiten erfassen zu k&ouml;nnen.");
                $tplAufgUADS->next();
            }
            $tplAufgDS->addToBufferBT($tplAufgUADS);
            $tplAufgUADS->reset();
        }
        
        // Unteraufgaben ausgeben
        if (isset($_GET['paid'])) {
            $c = AufgabeUtilities::loadChildrenAufgaben($_GET['paid']);
            
            foreach ($c as $unteraufgabe) {
                $tplUADS->replace("Bezeichnung", $unteraufgabe->getBezeichnung());
                $tplUADS->replace("ParentID", $ProjektAufgabe->getID());
                $tplUADS->replace("PaID", $unteraufgabe->getID());
                $tplUADS->next();
            }
            $tpl->replace("UnteraufgabenListe", $tplUADS->getOutput());
        }
        // Benutzerliste ausgeben
        $iAufgabeID = 0;
        if (isset($_GET['paid'])) {
            $iAufgabeID = intval($_GET['paid']);
        }
        $c = BenutzerUtilities::getMitarbeiterAufgabe($iAufgabeID);
        $tplMaDs = new Template("projekt_aufgabe_verwalten_mads.tpl");
        $strMA = "";
        $iCounter = 1;
        $iBenutzerProZeile = 3;
        foreach ($c as $benutzer) {
            $tplMaDs->replace("Benutzername", $benutzer->getBenutzername());
            $tplMaDs->replace("BenutzerID", $benutzer->getID());
            if ($benutzer->isFreigeschaltet())
                $tplMaDs->replace("checked", Constant::$HTML_CHECKED_CHECKED);
            $tplMaDs->replace("checked", "");
            $tplMaDs->replace("Disabled", "");
            
            if ($iCounter == 1)
                $strMA .= "<tr>";
            
            $strMA .= $tplMaDs->getOutputAndRestore();
            
            if ($iCounter % $iBenutzerProZeile == 0) {
                $strMA .= "</tr>";
                $iCounter = 0;
            }
            $iCounter ++;
        }
        
        if ($htmlStatus != null)
            $tpl->replace("Statusmeldung", $htmlStatus->getOutput());
        
        $tpl->replace("UnteraufgabeSubmit", "Hinzufügen");
        $tpl->replace("UnteraufgabeBezeichnung", "");
        $tpl->replace("BenutzerDisabled", "Disabled");
        $tpl->replace("UnteraufgabeDisabled", "Disabled");
        $tpl->replace("Submit", "Speichern");
        $tpl->replace("MitarbeiterAufgabenliste", $strMA);
        $tpl->replace("Aufgabenliste", $tplAufgDS->getOutput());
        $tpl->replace("Submit", "Speichern");
        $tpl->replace("PaID", $ProjektAufgabe->getID());
        $tpl->replace("Bezeichnung", $ProjektAufgabe->getBezeichnung());
        $tpl->replace("Beschreibung", $ProjektAufgabe->getBeschreibung());
        
        return $tpl;
    }
    /**
     * {@inheritDoc}
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {
        return; 
    }

    /**
     * {@inheritDoc}
     * @see PostRequestHandler::executePost()
     */
    public function executePost()
    {
        return $this->executeGet();
    }

}