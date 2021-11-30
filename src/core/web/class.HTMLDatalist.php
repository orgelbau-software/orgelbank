<?php

/**
 * HTML5 Datalist.
 * @author Stephan
 *
 */
class HTMLDatalist extends HTMLSelect
{

    public function __construct($c, $valueGetter)
    {
        parent::__construct($c, $valueGetter);
        $this->setTemplate(new Template("html_datalist_option.tpl"));
    }
}

?>
