<?php

class HTMLFehlerseite extends HTMLStatus
{

    public function __construct($nachricht = "", $level = 4, $fadeMessage = true)
    {
        parent::__construct($nachricht, $level, $fadeMessage);
        $this->pfad = "status_html_fehlerseite.tpl";
    }

    protected function init()
    {
        parent::init();
        $this->tpl->replace("InstanceUrl", INSTANCE_URL);
    }
}