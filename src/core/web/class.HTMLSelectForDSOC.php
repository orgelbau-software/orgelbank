<?php

class HTMLSelectForDSOC extends HTMLSelectForArray
{

    private $dsoc;

    private $getterMethod;

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
        $this->getterMethod = $bezeichnungsGetter;
        $this->dsoc = $c;
        parent::setSelectedId($iSelectedID);
    }

    protected function init()
    {
        $a = array();
        foreach ($this->dsoc as $objekt) {
            $tmpName = call_user_func(array(
                $objekt,
                $this->getterMethod
            ));
            $a[$objekt->getID()] = $tmpName;
        }
        $this->setCollection($a);
        parent::init();
    }
}

?>
