<?php

class HTMLSicherheitsAbfrage
{

    private $tpl;

    private $text;

    private $buttonJa;

    private $buttonNein;

    private $buttonNeinLink;

    private $formLink;

    private $objektID;

    public function __construct($text = "", $objektID = "", $formLink = "", $btnJa = "", $btnNein = "", $btnNeinLink = "")
    {
        $this->setText($text);
        $this->setFormLink($formLink);
        $this->setButtonJa($btnJa);
        $this->setButtonNein($btnNein);
        $this->setButtonNeinLink($btnNeinLink);
        $this->setObjektID($objektID);
    }

    public function init()
    {
        $this->tpl = new Template("html_sicherheitsabfrage.tpl");
        $this->tpl->replace("Text", $this->getText());
        $this->tpl->replace("ButtonJa", $this->getButtonJa());
        $this->tpl->replace("ButtonNein", $this->getButtonNein());
        $this->tpl->replace("ButtonNeinLink", $this->getButtonNeinLink());
        $this->tpl->replace("ZielLink", $this->getFormLink());
        $this->tpl->replace("ObjektID", $this->getObjektID());
    }

    public function anzeigen()
    {
        $this->init();
        echo $this->tpl->forceOutput();
    }

    public function getButtonJa()
    {
        return $this->buttonJa;
    }

    public function getButtonNein()
    {
        return $this->buttonNein;
    }

    public function getButtonNeinLink()
    {
        return $this->buttonNeinLink;
    }

    public function getFormLink()
    {
        return $this->formLink;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getTpl()
    {
        return $this->tpl;
    }

    public function setButtonJa($buttonJa)
    {
        $this->buttonJa = $buttonJa;
    }

    public function setButtonNein($buttonNein)
    {
        $this->buttonNein = $buttonNein;
    }

    public function setButtonNeinLink($buttonNeinLink)
    {
        $this->buttonNeinLink = $buttonNeinLink;
    }

    public function setFormLink($formLink)
    {
        $this->formLink = $formLink;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setTpl($tpl)
    {
        $this->tpl = $tpl;
    }

    public function getObjektID()
    {
        return $this->objektID;
    }

    public function setObjektID($objektID)
    {
        $this->objektID = $objektID;
    }
}
?>