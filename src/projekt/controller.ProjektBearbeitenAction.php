<?php

class ProjektBearbeitenAction implements GetRequestHandler, PostRequestHandler, PostRequestValidator
{

    /**
     * 
     * @var HTMLStatus
     */
    private $htmlStatus = null;

    private $postValid = false;

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {}

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {}

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $tpl = new Template("projekt_bearbeiten.tpl");
        
        if (isset($_POST['pid']) && $_POST['pid'] > 0) {
            $p = new Projekt($_POST['pid']);
            $tpl->replace("Titel", "bearbeiten");
            $tpl->replace("Disabled", "");
        } elseif (isset($_GET['pid'])) {
            $p = new Projekt($_GET['pid']);
            $tpl->replace("Titel", "bearbeiten");
            $tpl->replace("Disabled", "");
        } else {
            $p = new Projekt();
            $tpl->replace("Titel", "anlegen");
            $tpl->replace("Disabled", "disabled");
        }
        
        
        $strTmp = "";
        $htmlStatus = $this->htmlStatus;
        
        // Gemeindeauswahl
        $standardSortierung = ConstantLoader::getGemeindeListeStandardSortierung();
        if ($standardSortierung == "ort") {
            $htmlSelect = new HTMLSelectForKey(GemeindeUtilities::getGemeinden(" ORDER BY ad_ort"), "getGemeindeId", "getOrt,getKirche", $p->getGemeindeID());
        } else {
            $htmlSelect = new HTMLSelectForKey(GemeindeUtilities::getGemeinden(" ORDER BY g_kirche"), "getGemeindeId", "getKirche,getOrt", $p->getGemeindeID());
        }
       
        $tpl->replace("Gemeinden", $htmlSelect->getOutput());
        
        // Aufgaben
        $c = ProjektAufgabeUtilities::getSelectedProjektAufgaben($p->getID());
        $tplAufgaben = new BufferedTemplate("projekt_bearbeiten_aufg.tpl", "Odd", "td1", "td2");
        
        foreach ($c as $oAufgabe) {
            $tplAufgaben->replace("Aufgabe", $oAufgabe->getBezeichnung());
            if ($oAufgabe->isSelected()) {
                $tplAufgaben->replace("checked", "checked");
                $tplAufgaben->replace("readOnly", "");
                $tplAufgaben->replace("readOnlyClass", "");
            }
            $tplAufgaben->replace("checked", "");
            $tplAufgaben->replace("readOnly", "readOnly");
            $tplAufgaben->replace("readOnlyClass", "readOnly");
            $tplAufgaben->replace("PaID", $oAufgabe->getPKAufgabeID());
            $tplAufgaben->replace("Betrag", $oAufgabe->getPlankosten());
            $tplAufgaben->replace("SollStd", $oAufgabe->getSollStunden()); // Ja, ich weiß, aber so gehts. 
            $tplAufgaben->replace("SollMaterial", $oAufgabe->getSollMaterial());
            $tplAufgaben->next();
        }
        
        $tpl->replace("Projektaufgaben", $tplAufgaben->getOutput());
        
        // ProjektMitarbeiter
        $c = BenutzerUtilities::getBenutzer();
        $tplBenutzer = new BufferedTemplate("projekt_bearbeiten_mads.tpl");
        $benutzerKeineZeit = ( $p->getKeineZeitenFuer() == null ? array() : explode(",", $p->getKeineZeitenFuer()));
        
        $count = 0;
        foreach ($c as $benutzer) {
            $tplBenutzer->replace("BenutzerID", $benutzer->getID());
            $tplBenutzer->replace("Benutzername", $benutzer->getBenutzername());
            if (in_array($benutzer->getID(), $benutzerKeineZeit))
                $tplBenutzer->replace("checked", "checked");
            $tplBenutzer->replace("checked", "");
            
            if ($count ++ % 3 == 0)
                $tplBenutzer->append("</tr><tr>");
            
            $tplBenutzer->next();
        }
        for ($i = $count; $i % 3 != 0; $i ++) {
            $tplBenutzer->append("<td>&nbsp;</td><td>&nbsp;</td>");
        }
        $tpl->replace("Mitarbeiter", $tplBenutzer->getOutput());
        
        if (null != $htmlStatus)
            $tpl->replace("Statusmeldung", $htmlStatus->getOutput());
        $tpl->replace("Statusmeldung", "");
        
        $tpl->replace("Start", ($p->getStart(true) == "01.01.1970" ? "" : $p->getStart()));
        $tpl->replace("Ende", ($p->getEnde(true) == "01.01.1970" ? "" : $p->getEnde()));
        $tpl->replace("Beschreibung", $p->getBeschreibung());
        $tpl->replace("Bezeichnung", $p->getBezeichnung());
        $tpl->replace("ProjektID", $p->getID());
        $tpl->replace("Angebotspreis", WaehrungUtil::formatDoubleToWaehrung($p->getAngebotsPreis()));
        
        return $tpl;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {
        Utilities::escapePost();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::executePost()
     */
    public function executePost()
    {
        if($this->postValid) {
            $this->htmlStatus = new HTMLStatus();
            $p = new Projekt($_POST['pid']);

            if($_POST['beschreibung'] == "") {
                $p->setBeschreibung("(Keine Beschreibung)");
            } else {
                $p->setBeschreibung($_POST['beschreibung']);
            }   
            $p->setBezeichnung($_POST['bezeichnung']);
            $p->setStart($_POST['start']);
            $p->setEnde($_POST['ende']);
            $p->setGemeindeID(intval($_POST['gemeinde']));
            
            $_POST['angebotspreis'] = str_replace(".", "", $_POST['angebotspreis']);
            $p->setAngebotsPreis(WaehrungUtil::formatWaehrungToDB($_POST['angebotspreis']));
            
            if ($p->getGemeindeID() <= 0 || $p->getBezeichnung() == null || trim($p->getBezeichnung()) == "" || strtotime($p->getStart()) == false || strtotime($p->getEnde()) == false) {
                $this->htmlStatus->setText("Bitte Gemeinde, Bezeichnung, Start- und Enddatum ausw&auml;hlen");
                $this->htmlStatus->setStatusclass(1);
            } else {
                
                $keineZeitenFuer = "";
                foreach ($_POST as $key => $val) {
                    if (substr($key, 0, 3) == "be_") {
                        $iBenutzerID = substr($key, 3);
                        $keineZeitenFuer .= $iBenutzerID . ",";
                    }
                }
                $p->setKeineZeitenFuer($keineZeitenFuer);
                $p->speichern(true);
                
                ProjektAufgabeUtilities::resetProjektAufgaben($p->getID());
                $projektAufgabeReihenfolge = 0;
                foreach ($_POST as $key => $val) {
                    if (substr($key, 0, 11) == "aufgabe_id_") {
                        $iAufgabeID = substr($key, 11);
                        $pa = new ProjektAufgabe();
                        $pa->setPKaufgabeID($iAufgabeID);
                        $pa->setPKprojektID($p->getID());
                        $pa->setPlankosten($_POST['aufgabe_betrag_' . $iAufgabeID]);
                        $pa->setReihenfolge($projektAufgabeReihenfolge ++);
                        $pa->setSollMaterial($_POST['aufgabe_sollmaterial_'. $iAufgabeID]);
                        $pa->setSollStunden($_POST['aufgabe_sollstd_'. $iAufgabeID]);
                        $pa->speichern(false);
                    }
                }
                
                $this->htmlStatus->setText("Projektdetails gespeichert");
                $this->htmlStatus->setStatusclass(2);
            }
        }
        return $this->executeGet();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::validatePostRequest()
     */
    public function validatePostRequest()
    {
        if(!isset($_POST['pid'])) {
            $this->postValid = false;
        } else if($_POST['bezeichnung'] == null || trim($_POST['bezeichnung']) == "") {
            $this->postValid = false;
        } else if($_POST['start'] == null || trim($_POST['start']) == "") {
            $this->postValid = false;
        } else if($_POST['ende'] == null || trim($_POST['ende']) == "") {
            $this->postValid = false;
        } else {
            $this->postValid = true;
        }
        
        return $this->postValid;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::handleInvalidPost()
     */
    public function handleInvalidPost()
    {
        $this->htmlStatus = new HTMLStatus();
        $this->htmlStatus->setText("Bitte Gemeinde, Bezeichnung, Start- und Enddatum ausw&auml;hlen");
        $this->htmlStatus->setStatusclass(HTMLStatus::$STATUS_ERROR);
    }
}