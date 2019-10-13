<?php

class OptionValueAction implements GetRequestHandler, PostRequestHandler, PostRequestValidator
{

    /**
     *
     * @var Template
     */
    private $tplStatus = null;

    public function validatePostRequest()
    {
        return true;
    }

    public function handleInvalidPost()
    {}

    public function preparePost()
    {}

    public function executePost()
    {
        foreach ($_POST as $key => $val) {
            ConstantSetter::updateOption($key, addslashes($val));
        }
        $this->tplStatus = new HTMLStatus("Optionen gespeichert", 2);
        return $this->executeGet();
    }

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
        $tpl = new Template("einst_options.tpl");
        
        $r = OptionValueUtilities::getEditableOptions("ORDER BY option_modul");
        $tplDS = new BufferedTemplate("einst_options_ds.tpl");
        $tplRubrik = new BufferedTemplate("einst_options_rubrik.tpl");
        $letzteRubrik = "";
        foreach ($r as $current) {
            if ($letzteRubrik != $current->getModul()) {
                if ($letzteRubrik != "") {
                    $tplRubrik->replace("Content", $tplDS->getOutput());
                    $tplRubrik->next();
                    $tplDS->reset();
                }
                $tplRubrik->replace("Modulname", $current->getModul());
            }
            $tplDS->replace("Kommentar", $current->getComment());
            $tplDS->replace("OptionName", $current->getName());
            $tplDS->replace("OptionValue", $current->getValue());
            $tplDS->next();
            
            $letzteRubrik = $current->getModul();
        }
        
        // Letztes Modul auch noch hinzufuegen, sonst wird z.B. "Wartung" nicht mit ausgegeben.
        $tplRubrik->replace("Content", $tplDS->getOutput());
        $tplRubrik->next();
        
        $tpl->replace("Content", $tplRubrik->getOutput());
        
        if ($this->tplStatus != null) {
            $tpl->replace("Status", $this->tplStatus->getOutput());
        }
        return $tpl;
    }

    public function __toString()
    {
        return get_class($this);
    }
}