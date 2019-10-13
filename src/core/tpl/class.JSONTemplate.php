<?php

class JSONTemplate extends Template
{

    private $mObject;

    public function __construct($pObject)
    {
        $this->mObject = $pObject;
    }

    public function anzeigen()
    {
        echo json_encode($this->mObject);
    }
}