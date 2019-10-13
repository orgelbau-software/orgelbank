<?php

class SubPage
{

    private $identifier;

    private $minUserLvl;

    private $name;

    public function __construct($id, $functioName, $minUserLevel = 10)
    {
        $this->identifier = $id;
        $this->name = $functioName;
        $this->minUserLvl = $minUserLevel;
    }

    public function getMinUserLvl()
    {
        return $this->minUserLvl;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setChildren(HashTable $children)
    {
        $this->children = $children;
    }

    public function setMinUserLvl($minUserLvl)
    {
        $this->minUserLvl = $minUserLvl;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }
}
?>