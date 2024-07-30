<?php

/**
 * Klasse für HTML Statusmeldungen
 * 0 = EMPTY
 * 1 = ERROR
 * 2 = OK
 * 3 = WARNING
 * 4 = INFO
 *
 */
class HTMLStatus
{

    public static $STATUS_EMPTY = 0;
    public static $STATUS_ERROR = 1;
    public static $STATUS_OK = 2;
    public static $STATUS_WARN = 3;
    public static $STATUS_INFO = 4;
    
    protected $pfad = "status_nachricht_small.tpl";

    protected $tpl;

    protected $text;

    protected $classes = array(
        0 => "statusempty",
        1 => "statuserror",
        2 => "statusok",
        3 => "statuswarning",
        4 => "statusinfo"
    );

    protected $pics = array(
        0 => "",
        1 => "statuserrorpic",
        2 => "statusokpic",
        3 => "statuswarningpic",
        4 => "statusinfopic"
    );

    protected $statusPic;

    protected $statusclass;

    protected $noFadeClass;

    /**
     *
     * @param string $nachricht            
     * @param number $level
     *            0=EMPTY|1=ERROR|2=OK|3=WARNING|4=INFO
     * @param string $fadeMessage            
     */
    public function __construct($nachricht = "", $level = 4, $fadeMessage = true)
    {
        $this->text = $nachricht;
        $this->statusclass = $this->classes[intval($level)];
        $this->statusPic = $this->pics[$level];
        $this->noFadeClass = ($fadeMessage ? "" : "jsNoFade");
    }

    protected function init()
    {
        $this->tpl = new Template($this->pfad);
        $this->tpl->replace("StatusClass", $this->statusclass . " " . $this->noFadeClass);
        $this->tpl->replace("StatusPicClass", $this->statusPic);
        $this->tpl->replace("Nachricht", $this->text);
    }

    public function anzeigen()
    {
        $this->init();
        echo $this->tpl->forceOutput();
    }

    /**
     * 
     
     * @return string
     */
    public function getOutput()
    {
        $this->init();
        return $this->tpl->getOutput();
    }

    public function getTemplate()
    {
        $this->init();
        return $this->tpl;
    }

    public function setStatusclass($statusclass)
    {
        $this->statusclass = $this->classes[$statusclass];
        $this->statusPic = $this->pics[$statusclass];
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function __toString()
    {
        $this->init();
        return $this->tpl->forceOutput();
    }
}

