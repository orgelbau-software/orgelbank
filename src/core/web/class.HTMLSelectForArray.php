<?php

class HTMLSelectForArray implements HTMLSelectDescriptor
{

    /**
     * Template
     *
     * @var Template
     */
    private $tpl;

    private $str = "select_option.tpl";

    /**
     * DatabaseStorageObjektCollection
     *
     * @var DatabaseStorageObjektCollection
     */
    private $collection;

    private $selectedID;

    private $valueGetterMethod;

    private $keyGetterMethod;

    private $disabled;

    private $disabledTxt;

    /**
     * Standardkonstruktor zum erstellen einer neuen HTML Select Box
     *
     * @param ArrayList $c            
     * @param
     *            Bezeichnungs-Getter aus $c $bezeichnungsGetter
     * @param
     *            Ausgewählter Eintrag $iSelectedID
     */
    public function __construct(array $c, $selectedKeyValue = 0)
    {
        $this->collection = $c;
        $this->selectedID = $selectedKeyValue;
    }

    public function setTemplate(Template $t)
    {
        $this->tpl = $t;
    }

    protected function init()
    {
        $this->tpl = new BufferedTemplate($this->str);
        foreach ($this->collection as $key => $value) {
            $this->tpl->replace("Value", $key);
            $this->tpl->replace("Name", $value);
            
            if ($this->selectedID == $key) {
                $this->tpl->replace("Selected", Constant::$HTML_SELECTED_SELECTED);
            }
            
            $this->tpl->replace("Selected", "");
            $this->tpl->next();
        }
    }

    public function anzeigen()
    {
        $this->init();
        $this->tpl->anzeigen();
    }

    /**
     *
     * @return String
     */
    public function getOutput()
    {
        $this->init();
        return $this->tpl->getOutput();
    }

    /**
     *
     * @param array $c            
     */
    public function setCollection($c)
    {
        $this->collection = $c;
    }

    /**
     *
     * @return array
     */
    public function getGetterMethod()
    {
        return $this->getterMethod;
    }

    /**
     *
     * @param String $getterMethod            
     */
    public function setGetterMethod($getterMethod)
    {
        $this->getterMethod = $getterMethod;
    }

    protected function setSelectedId($id)
    {
        $this->selectedID = $id;
    }
}

?>
