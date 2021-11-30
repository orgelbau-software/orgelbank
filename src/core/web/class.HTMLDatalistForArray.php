<?php

class HTMLDatalistForArray extends HTMLSelectForArray
{

    public function __construct(array $c, $selectedKeyValue = 0)
    {
        parent::__construct($c, $selectedKeyValue);
        $this->setTemplate(new Template("html_datalist_option.tpl"));
    }
}

?>
