<?php

class BenutzerVerlaufUebersichtAction implements GetRequestHandler
{

    public function __construct()
    {}

    public function validateGetRequest()
    {
        return true;
    }

    public function handleInvalidGet()
    {}

    public function prepareGet()
    {}

    public function executeGet()
    {
        $tpl = new Template("einstellung_benutzerverlaufuebersicht.tpl");
        $tplDS = new BufferedTemplate("einstellung_benutzerverlaufuebersicht_ds.tpl", "CSS", "td1", "td2");
        
        $rs = BenutzerVerlaufUtilities::loadBenutzerVerlaufUebersicht();
        foreach ($rs as $current) {
            $tplDS->replace("Benutzername", $current->getBenutzername());
            $tplDS->replace("Count", $current->getCount());
            $tplDS->replace("Min", $current->getMin());
            $tplDS->replace("Max", $current->getMax());
            $tplDS->next();
        }
        
        $tpl->replace("TableContent", $tplDS->getOutput());
        return $tpl;
    }
}

?>