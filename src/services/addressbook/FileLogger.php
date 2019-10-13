<?php
namespace services\addressbook;

class FileLogger
{

    protected $handle = null;

    public function __construct()
    {
        $this->handle = fopen("out.log", "a");
    }

    public function log($pClass, $msg = "")
    {
        if (is_array($msg)) {
            $msg = print_r($msg, true);
        }
        fwrite($this->handle, date("d.m.Y H:i:s", time()) . "\t" . $pClass . "\t" . $msg . "\r\n");
    }

    public function __destruct()
    {
        fclose($this->handle);
    }
}

?>