<?php

/**
 * Objekt dieser Klasse repräsentiert ein dynamisches Array
 * @author Stephan Watermeyer <stephan@watermeyer.info>
 */
class ArrayList implements Iterator, ArrayAccess
{

    protected $values = null;

    protected $internalIndex = null;

    /**
     * Zählervariable die den Durchlauf mit einer foreach-Schleife verwaltet
     */
    protected $index = null;

    /**
     * Erzeugt eine neues Objekt ohne Elemente.
     */
    public function __construct()
    {
        $this->values = array();
        $this->index = 0;
        $this->internalIndex = 0;
    }

    /**
     * Fügt der Liste einen Wert hinzu.
     * 
     * @param mixed $value
     *            Wert der hinzugefügt werden soll
     */
    public function add($value, $val2 = "")
    {
        $this->values[$this->internalIndex] = $value;
        $this->internalIndex ++;
    }

    /**
     * Löscht das Element an der angegebenen Stelle.
     * 
     * @param int $index
     *            Stelle an der gelöscht werden soll
     */
    public function remove($index)
    {
        if ($this->values[$index]) {
            $i = 0;
            for ($i = $index; $i < $this->getSize() - 1; $i ++) {
                $this->values[$i] = $this->values[$i + 1];
            }
            $this->values[$i] = null;
            $this->internalIndex --;
        }
    }

    /**
     * Alias für removeAll.
     * 
     * @deprecated
     *
     * @see removeAll()
     */
    public function clear()
    {
        $this->removeAll();
    }

    /**
     * Löscht alle Werte der ArraList
     */
    public function removeAll()
    {
        $this->internalIndex = 0;
    }

    /**
     * Liest einen Wert aus der ArrayList aus
     * 
     * @param int $index
     *            Stelle dies ausgelsen werden soll
     * @return mixed $value Wert aus der ArrayList
     */
    public function getValueOf($index)
    {
        if ($index < $this->getSize() && $index >= 0) {
            return $this->values[$index];
        } else {
            return null;
        }
    }

    public function setValueOf($index, $value)
    {
        if ($index < $this->getSize() && $index >= 0) {
            $this->values[$index] = $value;
        }
    }

    /**
     * Sucht nach einem Wert und liefert den Index
     * 
     * @param mixed $value
     *            Wert der gesucht werden soll
     * @return int $index Index an der der Wert gefunden wurde, falls er nciht gefunden wird, wird FALSE zurückgegeben
     */
    public function indexOf($value)
    {
        return array_search($value, $this->values);
    }

    /**
     * Gibt die aktuelle Größe der ArrayList aus
     * 
     * @return int $size Anzahl der Elemente in der ArrayList
     */
    public function getSize()
    {
        return $this->internalIndex;
    }

    /**
     * Überprüft ob ein Wert in der ArrayList existiert
     * 
     * @param mixed $value
     *            Wert, der enthalten sein soll
     * @return bool $gefunden Gibt an, ob $value gefunden wurde
     */
    public function contains($value)
    {
        if (array_search($value, $this->values) === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Setzte den Zähler für den foreach-Durchlauf zurück
     * 
     * @see Iterator
     */
    public function rewind() : void
    {
        $this->index = 0;
    }

    /**
     * Überprüft ob ein weiterer Schleifendurchgang notwendig ist
     * 
     * @see Iterator
     * @return bool Gibt an ob ein weiterer Schleifendurchgang notwendig ist
     */
    public function valid() : bool
    {
        return $this->index < $this->getSize();
    }

    /**
     * Diese Funktion ist notwendig um die Schlüssel beim foreach-Durchlauf auslesen zu können
     * 
     * @see Iterator
     * @return int $index Gibt den aktuellen Arrayschlüssel zurück
     */
    public function key() : mixed
    {
        return $this->index;
    }

    /**
     * Diese Funktion ist notwendig um den aktuellen Wert beim foreach-Durchlauf auslesen zu können
     * 
     * @see Iterator
     * @return mixed $value Wert an der aktuellen Position
     */
    public function current() : mixed
    {
        return $this->values[$this->index];
    }

    /**
     * Rückt den internen Zeiger um eine Position vor
     * 
     * @see Iterator
     */
    public function next() : void
    {
        $this->index ++;
    }

    /**
     * Überprüft ob der angegebene Arrayindex existiert
     * 
     * @see ArrayAccess
     * @param int $offset
     *            Der angegebene Arrayindex
     */
    public function offsetExists($offset) : bool
    {
        return $offset < $this->getSize() && $offset >= 0;
    }

    /**
     * Liefert beim Arrayzugriff den gewünschten Wert zurück, falls dieser existiert
     * 
     * @see ArrayAccess
     * @param int $offset
     *            Der angegebene Arrayindex
     * @return mixed $value Wert an der ArrayList am Index $offset
     */
    public function offsetGet($offset) : mixed
    {
        if ($this->offsetExists($offset)) {
            return $this->getValueOf($offset);
        } else {
            return null;
        }
    }

    /**
     * Fügt bei Arrayzugriff der ArrayList eine neues Element hinzu, falls der angegbene Index der nächst mögliche ist
     * 
     * @see ArrayAccess
     * @param int $offset
     *            Der angegebene Arrayindex
     * @param mixed $value
     *            Wert auf der unter dem angegebenen Index gespeichert werden soll
     */
    public function offsetSet($offset, $value) : void
    {
        if ($offset == $this->getSize()) {
            $this->add($value);
        } else if ($this->offsetExists($offset)) {
            $this->setValueOf($offset, $value);
        }
    }

    /**
     * Entfernt beim Aufruf von unset() auf ein Arrayelement den Wert aus der ArrayList
     * 
     * @see ArrayAccess
     * @param int $offset
     *            Der angegebene Arrayindex
     */
    public function offsetUnset($offset) : void
    {
        if ($this->offsetExists($offset)) {
            $this->remove($offset);
        }
    }

    public function getLength()
    {
        return $this->internalIndex;
    }

    public function __toString()
    {
        return "ArrayList[]";
    }
}
?>