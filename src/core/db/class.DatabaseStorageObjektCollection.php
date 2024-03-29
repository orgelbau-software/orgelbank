<?php

/**
 * Gekapselte Collection fuer Objekte die von DataBaseStorageObjekt erben
 * 
 * @author swatermeyer
 * @version $Revision:  $
 */
class DatabaseStorageObjektCollection implements Iterator, ArrayAccess
{

    private $list;

    /**
     * Standardkonstruktor
     */
    public function __construct()
    {
        $this->list = new ArrayList();
    }

    /**
     * Fuegt ein Objekt hinzu
     *
     * @param DatabaseStorageObjekt $o            
     */
    public function add(DatabaseStorageObjekt $o)
    {
        $this->list->add($o);
    }

    /**
     * Entfernt ein Objekt aus der Collection
     *
     * @param int $index            
     */
    public function remove($index)
    {
        $this->list->remove($index);
    }

    /**
     * Gibt das Element an der Stelle zurueck
     *
     * @param int $index            
     * @return DatabaseStorageObjekt
     */
    public function getValueOf($index)
    {
        return $this->list->getValueOf($index);
    }

    /**
     * Setzt ein Element an der Stelle
     *
     * @param int $index            
     * @param DatabaseStorageObjekt $o            
     */
    public function setValueOf($index, DatabaseStorageObjekt $o)
    {
        $this->list->setValueOf($index, $o);
    }

    /**
     * Ruft die speichern Methode jedes Elementes in der Collection auf
     *
     * @param boolean $objektNachSpeichernNeuLaden            
     */
    public function speichern($objektNachSpeichernNeuLaden = true)
    {
        foreach ($this as $x)
            $x->speichern($objektNachSpeichernNeuLaden);
    }

    /**
     * Überprüft ob ein Wert in der ArrayList existiert
     * 
     * @param mixed $value
     *            Wert, der enthalten sein soll
     * @return bool $gefunden Gibt an, ob $value gefunden wurde
     */
    public function contains(DatabaseStorageObjekt $obj)
    {
        $retVal = false;
        foreach ($this->values as $elem) {
            if ($elem->getID() == $obj->getID()) {
                $retVal = true;
            }
        }
        return $retVal;
    }

    public function current() : mixed
    {
        return $this->list->current();
    }

    public function next() : void
    {
        //return $this->list->next();
        $this->list->next();
    }

    public function key() : mixed
    {
        return $this->list->key();
    }

    public function valid() : bool
    {
        return $this->list->valid();
    }

    public function rewind() : void
    {
        //return $this->list->rewind();
        $this->list->rewind();
    }

    public function offsetExists($offset) : bool    
    {
        return $this->list->offsetExists($offset);
    }

    public function offsetGet($offset) : mixed
    {
        return $this->list->offsetGet($offset);
    }

    public function offsetSet($offset, $value) : void
    {
        $this->list->offsetSet($offset, $value);
    }

    public function offsetUnset($offset) : void
    {
         $this->list->offsetUnset($offset);
    }

    public function getSize()
    {
        return $this->list->getSize();
    }
}
?>