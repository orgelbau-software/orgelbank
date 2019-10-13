<?php

/**
 * @deprecated use instead BufferedTemplate
 *
 */
class TemplateRepeater extends Template
{

    private $css1;

    private $css2;

    private $csscurrent;

    private $tpltag;

    public function __construct($pfad, $tpltag, $css1, $css2)
    {
        $this->css1 = $css1;
        $this->css2 = $css2;
        $this->csscurrent = $css1;
        $this->tpltag = $tpltag;
        parent::__construct($pfad);
    }

    public function getOutput()
    {
        $this->replace($this->tpltag, $this->csscurrent);
        $this->farbswitch();
        return parent::getOutput();
    }

    public function forceOutput()
    {
        $this->replace($this->tpltag, $this->csscurrent);
        $this->farbswitch();
        return parent::forceOutput();
    }

    private function farbswitch()
    {
        if ($this->csscurrent == $this->css1) {
            $this->csscurrent = $this->css2;
        } else {
            $this->csscurrent = $this->css1;
        }
    }
}
?>
