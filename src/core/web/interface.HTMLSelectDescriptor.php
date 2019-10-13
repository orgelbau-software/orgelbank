<?php

interface HTMLSelectDescriptor
{

    /**
     *
     * @param Template $t            
     */
    public function setTemplate(Template $t);

    /**
     */
    public function anzeigen();

    /**
     *
     * @return String
     */
    public function getOutput();

    /**
     *
     * @param array $c            
     */
    public function setCollection($c);

    /**
     *
     * @return String
     */
    public function getGetterMethod();

    /**
     *
     * @param String $getterMethod            
     */
    public function setGetterMethod($getterMethod);
}
?>