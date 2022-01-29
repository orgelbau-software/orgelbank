<?php

class HTMLSelectForKey extends HTMLSelectForArray
{

    private $selectedID;

    private $valueGetterMethod;

    private $keyGetterMethod;

    private $disabled;

    private $disabledTxt;

    private $arraylist;

    private $valueMaxLength = 0;

    /**
     * Standardkonstruktor zum erstellen einer neuen HTML Select Box
     *
     * @param ArrayList $c            
     * @param
     *            Bezeichnungs-Getter aus $c $bezeichnungsGetter
     * @param
     *            Ausgewählter Eintrag $iSelectedID
     */
    public function __construct(ArrayList $c, $keyGetter, $valueGetter, $selectedKeyValue = 0)
    {
        parent::__construct(array(), $selectedKeyValue);
        $this->keyGetterMethod = $keyGetter;
        $this->valueGetterMethod = explode(",", $valueGetter);
        $this->arraylist = $c;
    }

    protected function init()
    {
        $a = array();
        foreach ($this->arraylist as $objekt) {
            $tmpValue = "";
            foreach ($this->valueGetterMethod as $currGetter) {
                if ($tmpValue != "") {
                    $tmpValue .= ", ";
                }
                $tmpValue .= call_user_func(array(
                    $objekt,
                    $currGetter
                ));
            }
            
            if ($this->keyGetterMethod != "") {
                $tmpKey = call_user_func(array(
                    $objekt,
                    $this->keyGetterMethod
                ));
            } else {
                $tmpKey = $tmpValue;
            }
            
            if($this->valueMaxLength > 0 && strlen($tmpValue) > $this->valueMaxLength) {
                $tmpValue = substr($tmpValue, 0, $this->valueMaxLength -1) . "...";
            }
            $a[$tmpKey] = $tmpValue;
        }
        $this->setCollection($a);
        parent::init();
    }

    /**
     *
     * @return the $valueMaxLength
     */
    public function getValueMaxLength()
    {
        return $this->valueMaxLength;
    }

    /**
     *
     * @param number $valueMaxLength            
     */
    public function setValueMaxLength($valueMaxLength)
    {
        $this->valueMaxLength = $valueMaxLength;
    }
}

?>
