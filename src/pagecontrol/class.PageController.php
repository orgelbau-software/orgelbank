<?php

class PageController
{

    private $identifier;

    private $minUserLvl;

    private $children;

    private $name;

    public function __construct($id, $functioName, $minUserLevel = 10, HashTable $childPages = null)
    {
        $this->identifier = $id;
        $this->name = $functioName;
        $this->minUserLvl = $minUserLevel;
        $this->children = $childPages;
    }

    public function addChild(SubPage $child)
    {
        $this->getChildren()->add($child->getIdentifier(), $child);
    }

    public function getChild($child)
    {
        if ($child instanceof Page) {
            $child = $child->getIdentifier();
        }
        return $this->getChildren()->getValueOf($child);
    }

    /**
     *
     * @return HashTable
     */
    public function getChildren()
    {
        if (null == $this->children)
            $this->children = new HashTable();
        return $this->children;
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