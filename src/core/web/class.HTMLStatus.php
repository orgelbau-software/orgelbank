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

    /**
     * 
     * @var Template
     */
    protected $tpl;

    /**
     * 
     * @var string
     */
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

    /**
     * 
     * @var string
     */
    protected $statusPic;

    /**
     *
     * @var string
     */
    protected $statusclass;
    
    /**
     * 
     * @var string
     */
    protected $noFadeClass;

    /**
     *
     * @param string $nachricht            
     * @param int $level
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

    public function getStatusclass() {
        return $this->statusclass;
    }

    /**
     * 
     * @param string $text 
     * @return void 
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    public function __toString()
    {
        $this->init();
        return $this->tpl->forceOutput();
    }
}

