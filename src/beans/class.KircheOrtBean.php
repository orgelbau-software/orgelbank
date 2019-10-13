<?php

class KircheOrtBean implements Bean
{

    private $gemeindeId;

    private $kirche;

    private $ort;

    public function init($rs)
    {
        $this->setGemeindeId($rs['g_id']);
        $this->setKirche($rs['g_kirche']);
        $this->setOrt($rs['ad_ort']);
    }

    public function getID()
    {
        return $this->getGemeindeId();
    }

    public function getGemeindeId()
    {
        return $this->gemeindeId;
    }

    public function setGemeindeId($gemeindeId)
    {
        $this->gemeindeId = $gemeindeId;
    }

    public function getKirche()
    {
        return $this->kirche;
    }

    public function getOrt()
    {
        return $this->ort;
    }

    public function setKirche($kirche)
    {
        $this->kirche = $kirche;
    }

    public function setOrt($ort)
    {
        $this->ort = $ort;
    }
}