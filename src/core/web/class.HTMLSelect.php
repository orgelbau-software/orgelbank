<?php

class HTMLSelect implements HTMLSelectDescriptor
{

    /**
     *
     * @var HTMLSelectDescriptor
     */
    private $instance;

    /**
     * Standardkonstruktor zum erstellen einer neuen HTML Select Box
     *
     * @param DatabaseStorageObjektCollection $c            
     * @param
     *            Bezeichnungs-Getter aus $c $bezeichnungsGetter
     * @param
     *            Ausgewählter Eintrag $iSelectedID
     */
    public function __construct($c, $valueGetter, $iSelectedID = 0, $keyGetter = "")
    {
        $this->instance = null;
        // if($c instanceof DatabaseStorageObjektCollection && strpos($valueGetter, ",") > 0) {
        // $this->instance = new HTMLSelectForDSOCWithMultiGetter($c, $valueGetter, $iSelectedID);
        // } else
        if ($c instanceof DatabaseStorageObjektCollection) {
            $this->instance = new HTMLSelectForDSOC($c, $valueGetter, $iSelectedID);
        } elseif ($c instanceof ArrayList) {
            $this->instance = new HTMLSelectForKey($c, $keyGetter, $valueGetter, $iSelectedID);
        } elseif (is_array($c)) {
            $this->instance = new HTMLSelectForArray($c, $iSelectedID);
        } else {
            throw new Exception("type not supported as collection for HTMLSelect");
        }
    }

    public function setTemplate(Template $t)
    {
        $this->instance->setTemplate($t);
    }

    private function init()
    {
        $this->instance->init();
    }

    public function anzeigen()
    {
        $this->instance->anzeigen();
    }

    public function getOutput()
    {
        return $this->instance->getOutput();
    }

    public function setCollection($c)
    {
        $this->instance->setCollection($c);
    }

    public function getGetterMethod()
    {
        return $this->instance->getGetterMethod();
    }

    public function setGetterMethod($getterMethod)
    {
        $this->instance->setGetterMethod($getterMethod);
    }
}

?>
