<?php

class HTMLSelectForDSOCWithMultiGetter extends HTMLSelectForArray
{

    private $dsoc;

    private $getterMethods;

    private $disabled;

    private $disabledTxt;

    /**
     * Standardkonstruktor zum erstellen einer neuen HTML Select Box
     *
     * @param DatabaseStorageObjektCollection $c            
     * @param
     *            Bezeichnungs-Getter aus $c $bezeichnungsGetter
     * @param
     *            Ausgewählter Eintrag $iSelectedID
     */
    public function __construct(DatabaseStorageObjektCollection $c, $bezeichnungsGetter, $iSelectedID = 0)
    {
        $this->getterMethods = explode(",", $bezeichnungsGetter);
        $this->dsoc = $c;
        parent::setSelectedId($iSelectedID);
    }

    protected function init()
    {
        $a = array();
        foreach ($this->dsoc as $objekt) {
            $tmpName = "";
            foreach ($this->getterMethods as $currGetter) {
                if ($tmpName != "") {
                    $tmpName .= ", ";
                }
                $tmpName .= call_user_func(array(
                    $objekt,
                    $currGetter
                ));
            }
            $a[$objekt->getID()] = $tmpName;
        }
        $this->setCollection($a);
        parent::init();
    }
}

?>
