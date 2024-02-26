<?php

/**
 * Objekt diser Klasse repräsentiert ein dynamisches assoziatives Array
 * @package Collections
 * @author Stephan Watermeyer <stephan@watermeyer.info>
 * @version 0.1
 */
class HashTable extends ArrayList
{

    private $keys = null;

    public function __construct()
    {
        parent::__construct();
        $this->keys = new ArrayList();
    }

    /**
     *
     * @param mixed $key
     *            Schlüssel unter dem das Element gespeichert wird
     * @param mixed $value
     *            Element das gespeichert werden soll
     * @exception Wirft eine Exception, falls der gewünschte Schlüssel schon vorhanden ist
     */
    public function add($key, $value = "")
    {
        if (! $this->keys->contains($key)) {
            $this->keys->add($key);
            parent::add($value);
        } else {
            throw new Exception("Key already exists!");
        }
    }

    /**
     *
     * @param mixed $key
     *            Schlüssel unter dem das Element gespeichert wird
     * @param mixed $value
     *            Element das gespeichert werden soll
     * @exception Wirft eine Exception, falls der gewünschte Schlüssel schon vorhanden ist
     */
    public function put($key, $value)
    {
        $this->add($key, $value);
    }

    public function getValueOf($key)
    {
        if ($this->keys->contains($key)) {
            return parent::getValueOf($this->keys->indexOf($key));
        } else {
            return false;
        }
    }

    public function setValueOf($index, $value)
    {
        if ($this->containsKey($index)) {
            parent::setValueOf($this->keys->indexOf($index), $value);
        }
    }

    public function remove($key)
    {
        $index = $this->keys->indexOf($key);
        parent::remove($index);
        $this->keys->remove($index);
    }

    public function removeAll()
    {
        $this->keys->removeAll();
        parent::removeAll();
    }

    /**
     * Überpürft ob ein Schlüssel vergeben ist
     * 
     * @param mixed $key
     *            Schlüssel der überprüft werden soll
     * @return bool Wahrheitswert der Überprüfung
     */
    public function containsKey($key)
    {
        return $this->keys->contains($key);
    }

    /**
     * Alias für removeAll
     * 
     * @see removeAll()
     */
    public function clear()
    {
        $this->removeAll();
    }

    /**
     * Gibt den Schlüssel eines Elementes zurück
     * 
     * @return mixed Schlüssel eines Elementes
     */
    public function key() : mixed
    {
        return $this->keys->getValueOf($this->index);
    }

    public function offsetExists($offset) : bool
    {
        return $this->containsKey($offset);
    }

    public function offsetSet($offset, $value) : void
    {
        if ($this->containsKey($offset)) {
            $this->setValueOf($offset, $value);
        } else {
            $this->add($offset, $value);
        }
    }
}
?>