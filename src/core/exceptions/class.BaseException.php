<?php

abstract class BaseException extends Exception
{

    public function __toString()
    {
        return get_class() . " in " . $this->getFile() . ":" . $this->getLine() . "('" . $this->getMessage() . "')\nStack Trace:\n" . $this->getTraceAsString();
    }
}
?>