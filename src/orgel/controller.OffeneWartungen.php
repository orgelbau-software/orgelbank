<?php

class OffeneWartungen implements GetRequestHandler, PostRequestHandler
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
        $tpl = new Template("orgel_liste_wartungen.tpl");
        $tplDS = new BufferedTemplate("orgel_liste_wartungen_ds.tpl", "Farbwechsel", "td1", "td2");
        $tplRubrik = new Template("orgel_liste_wartungen_rubrik.tpl");
        $tplRubrikEnde = new Template("orgel_liste_wartungen_rubrik_ende.tpl");
        
        $handler = new OrgelOffeneWartungenRequestHandler();
        $handler = $handler->handleRequest();
        
        $tpl->replace("Zyklus" . $handler['zyklus'], Constant::$HTML_SELECTED_SELECTED);
        foreach (Constant::getZyklus() as $zahl => $text) {
            $tpl->replace("Zyklus" . $zahl, "");
        }
        
        $tpl->replace("hideunknown", ($handler['hideunknown'] ? Constant::$HTML_CHECKED_CHECKED : ""));
        
        $cOrgelListe = OrgelUtilities::getOrgelListeEingeplanteWartungen();
        $tmpJahr = 0;
        
        // Aktuell geplante aber nicht eingetragene Wartungen
        if ($cOrgelListe->getSize() > 0) {
            $tplRubrik->replace("Rubrik", "!");
            $tplDS->addToBuffer($tplRubrik);
            $tplRubrik->restoreTemplate();
            foreach ($cOrgelListe as $orgel) {
                $tplDS->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
                $tplDS->replace("OID", $orgel->getOrgelId());
                $tplDS->replace("LetztePflege", $orgel->getLetztePflege(true));
                $tplDS->replace("OID", $orgel->getOrgelId());
                $tplDS->replace("GID", $orgel->getGemeindeID());
                $tplDS->replace("Gemeinde", $orgel->getGemeindeNamen());
                $tplDS->replace("LetztePflege", $orgel->getLetztePflege(true));
                $tplDS->replace("Register", $orgel->getRegisterAnzahl());
                $tplDS->replace("PLZ", $orgel->getGemeindePLZ());
                $tplDS->replace("Ort", $orgel->getGemeindeOrt());
                $tplDS->replace("Bezirk", $orgel->getGemeindeBezirk());
                $tplDS->replace("Zyklus", $orgel->getZyklus());
                // Missbrauch des Baujahrs Feld um die WartungsId zu uebertragen
                $tplDS->replace("NaechstePflege", "<a href=\"index.php?page=2&do=28&oid=134&action=edit&wid=" . $orgel->getBaujahr() . "\">Zur Wartung</a>");
                $tplDS->replace("AnzahlRegister", $orgel->getRegisterAnzahl());
                $tplDS->next();
            }
        }
        
        $cOrgelListe = OrgelUtilities::getOrgelListeAnstehendeWartungen($handler['SQLADD']);
        $tmpJahr = 0;
        
        $tpl->replace("AnzahlWartungen", $cOrgelListe->getSize());
        foreach ($cOrgelListe as $orgel) {
            $naechstePflege = strtotime($orgel->getNaechstePflege());
            
            $dateNaechstePflege = date('Y', $naechstePflege);
            if ($tmpJahr != $dateNaechstePflege) {
                if ($tmpJahr != 0) {
                    $tplDS->addToBuffer($tplRubrikEnde);
                }
                $tpl->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
                $tmpJahr = date("Y", $naechstePflege);
                
                $tplRubrik->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
                $tplDS->addToBuffer($tplRubrik);
                $tplRubrik->restoreTemplate();
            }
            
            $tpl->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
            
            $naechstePflege = date("d.m.Y", $naechstePflege);
            if ($tmpJahr < 1990)
                $naechstePflege = "unbekannt";
            
            $tplDS->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
            $tplDS->replace("OID", $orgel->getOrgelId());
            $tplDS->replace("LetztePflege", $orgel->getLetztePflege(true));
            $tplDS->replace("OID", $orgel->getOrgelId());
            $tplDS->replace("GID", $orgel->getGemeindeID());
            $tplDS->replace("Gemeinde", $orgel->getGemeindeNamen());
            $tplDS->replace("LetztePflege", $orgel->getLetztePflege(true));
            $tplDS->replace("Register", $orgel->getRegisterAnzahl());
            $tplDS->replace("PLZ", $orgel->getGemeindePLZ());
            $tplDS->replace("Ort", $orgel->getGemeindeOrt());
            $tplDS->replace("Bezirk", $orgel->getGemeindeBezirk());
            $tplDS->replace("Zyklus", $orgel->getZyklus());
            $tplDS->replace("NaechstePflege", $naechstePflege);
            $tplDS->replace("AnzahlRegister", $orgel->getRegisterAnzahl());
            $tplDS->next();
            
            $tmpJahr = $dateNaechstePflege;
        }
        
        $tpl->replace("Content", $tplDS->getOutput());
        return $tpl;
    }

    public function preparePost()
    {
        return;
    }

    public function executePost()
    {
        return $this->executeGet();
    }
}