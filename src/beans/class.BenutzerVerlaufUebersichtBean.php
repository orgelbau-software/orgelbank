<?php

class BenutzerVerlaufUebersichtBean implements Bean
{

    private $benutzername;

    private $count;

    private $max;

    private $min;

    public function init($rs)
    {
        $this->setBenutzername($rs['bv_benutzername']);
        $this->setCount($rs['bv_count']);
        $this->setMin($rs['bv_min']);
        $this->setMax($rs['bv_max']);
    }

    /**
     *
     * @return the $benutzername
     */
    public function getBenutzername()
    {
        return $this->benutzername;
    }

    /**
     *
     * @return the $count
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     *
     * @return the $max
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     *
     * @return the $min
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     *
     * @param field_type $benutzername            
     */
    public function setBenutzername($benutzername)
    {
        $this->benutzername = $benutzername;
    }

    /**
     *
     * @param field_type $count            
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     *
     * @param field_type $max            
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     *
     * @param field_type $min            
     */
    public function setMin($min)
    {
        $this->min = $min;
    }
}

?>