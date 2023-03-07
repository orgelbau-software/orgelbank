<?php

class ProjektArchiv implements GetRequestHandler
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
        $tpl = new Template("projekt_archiv.tpl");
        $tplDS = new BufferedTemplate("projekt_archiv_ds.tpl", "CSS", "td1", "td2");
        $htmlStatus = null;
        
        if (isset($_GET['pid'], $_GET['a'])) {
            $p = new Projekt(intval($_GET['pid']));
            $htmlStatus = new HTMLStatus();
            
            if ($_GET['a'] == "d") {
                $p->loeschen();
                $p->speichern(false);
                $htmlStatus->setText("Projekt gel&ouml;scht.");
                $htmlStatus->setStatusclass(2);
            } elseif ($_GET['a'] == "r") {
                $p->setArchviert(0);
                $p->setArchivdatum(0);
                $p->speichern(true);
                $htmlStatus->setText("Projekt wiederhergestellt.");
                $htmlStatus->setStatusclass(2);
            } else {
                $htmlStatus->setText("Ung&uuml;ltige Eingabe.");
                $htmlStatus->setStatusclass(1);
            }
        }
        
        $c = ProjektUtilities::getArchivierteProjekte();
        foreach ($c as $projekt) {
            $tplDS->replace("ProjektID", $projekt->getID());
            $g = new Gemeinde($projekt->getGemeindeID());
            $tplDS->replace("GemeindeBezeichnung", $g->getKirche());
            $tplDS->replace("Bezeichnung", $projekt->getBezeichnung());
            $tplDS->replace("Starttermin", $projekt->getStart(true));
            $tplDS->replace("Endtermin", $projekt->getEnde(true));
            $tplDS->replace("Archiviert", $projekt->getArchivdatum(true));
            $tplDS->next();
        }
        
        if ($htmlStatus != null)
            $tpl->replace("Statusmeldung", $htmlStatus->getOutput());
        $tpl->replace("Statusmeldung", "");
        $tpl->replace("Projektliste", $tplDS->getOutput());
        
        return $tpl;
    }
}