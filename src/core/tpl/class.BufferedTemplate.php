<?php

class BufferedTemplate extends Template
{

    private $buffer;

    private $cssKlassenTag;

    private $cssKlasse1;

    private $cssKlasse2;

    private $cssCurrent;

    private $counter = 0;

    public function __construct($pfad, $cssKlassenTag = "", $cssKlasse1 = "", $cssKlasse2 = "")
    {
        parent::__construct($pfad);
        $this->setCSSKlasse1($cssKlasse1);
        $this->setCSSKlasse2($cssKlasse2);
        $this->setCSSKlassenTag($cssKlassenTag);
        $this->cssCurrent = $this->getCSSKlasse1();
    }

    public function next()
    {
        if ($this->getCSSKlassenTag() != "") {
            $this->replace($this->getCSSKlassenTag(), $this->cssCurrent);
            $this->farbswitch();
        }
        
        $this->buffer .= $this->forceOutput();
        $this->counter ++;
        $this->restoreTemplate();
    }

    public function getOutput()
    {
        return $this->buffer;
    }

    public function anzeigen()
    {
        echo $this->buffer;
    }

    private function farbswitch()
    {
        if ($this->cssCurrent == $this->cssKlasse1) {
            $this->cssCurrent = $this->cssKlasse2;
        } else {
            $this->cssCurrent = $this->cssKlasse1;
        }
        return $this->cssCurrent;
    }

    public function addToBuffer(Template $s)
    {
        $this->buffer .= $s->forceOutput();
    }

    public function append($s)
    {
        $this->buffer .= $s;
    }

    public function addToBufferBT(BufferedTemplate $s)
    {
        $this->buffer .= $s->getOutput();
    }

    public function replaceInBuffer($key, $value)
    {
        $this->buffer = str_replace("<!--" . $key . "-->", $value, $this->buffer);
        $this->buffer = str_replace("<!-- " . $key . " -->", $value, $this->buffer);
    }

    public function getCSSKlasse1()
    {
        return $this->cssKlasse1;
    }

    public function getCSSKlasse2()
    {
        return $this->cssKlasse2;
    }

    public function getCSSKlassenTag()
    {
        return $this->cssKlassenTag;
    }

    public function setCSSKlasse1($cssKlasse1)
    {
        $this->cssKlasse1 = $cssKlasse1;
    }

    public function setCSSKlasse2($cssKlasse2)
    {
        $this->cssKlasse2 = $cssKlasse2;
    }

    public function setCSSKlassenTag($cssKlassenTag)
    {
        $this->cssKlassenTag = $cssKlassenTag;
    }

    public function getCSSCurrent()
    {
        return $this->cssCurrent;
    }

    public function setCSSCurrent($cssCurrent)
    {
        $this->cssCurrent = $cssCurrent;
    }

    public function reset()
    {
        parent::restoreTemplate();
        $this->buffer = "";
        $this->cssCurrent = $this->cssKlasse1;
    }

    public function getNextCount()
    {
        return $this->counter;
    }

    public function isFirstRun()
    {
        return $this->getNextCount() == 0;
    }
}
?>