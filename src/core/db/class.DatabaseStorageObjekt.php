<?php

/**
 * Basisklasse fast aller Objekte mit Datenbankbezug
 * Stellt O/R-Mapping Funktionalität bereit.
 *
 * @author swatermeyer
 * @date 23.08.2007
 * @name DatabaseStorageObjekt
 * @version $Revision:  $
 */
abstract class DatabaseStorageObjekt
{

    const MYSQL_DATETIME_FORMAT = "Y-m-d H:i:s";
    
    protected $iID;

    protected $strChangeBy;

    protected $strCreatedAt;

    protected $dtChangeAt;

    protected $boObjektHasChanged;

    /**
     * Ausgewähltes Objekt.
     * Da in Collections oder in Dialogen generell öfter mal ein Objekt
     * "ausgewählt" wird und so einen Merker haben muss besitzt diese
     * Basisklasse eine Objektvariable zum Abrufen des "Ausgewählt" Status.
     *
     * Die Variable wird nicht gespeichert.
     *
     * @var unknown_type
     */
    protected $boSelected;

    /**
     * Objekt ist schon in der Datenbank gespeichert
     *
     * @var boolean
     */
    protected $isPersistent;

    protected $dbInstance;

    /**
     * Standarkonstruktor
     *
     * @access public
     * @param int/sql $iID            
     */
    public function __construct($iID = 0)
    {
        $this->dbInstance = DB::getInstance();
        if($iID == "") {
            $this->iID = 0;
        } else {
            $this->iID = $iID;
        }
        
        if ($this->iID != 0) {
            $this->load();
        } else {
            $this->newObjekt();
        }
    }

    /**
     * Stoesst den Ladevorgang eines Datensatzes an
     *
     * @access public
     * @param int/sql $iID            
     */
    public function load()
    {
        $this->doLoad();
        $this->afterLoad();
    }

    /**
     * Laedt den Datensatz aus der Datenbank
     *
     * @access protected
     * @param int/sql $iID            
     */
    protected abstract function doLoad();

    /**
     * Fuehrt Verwaltungsaufgabe nach dem Laden eines Objektes durch
     *
     * @access private
     */
    private function afterLoad()
    {
        if ($this->getID() != 0 && $this->getID() !== - 1) {
            $this->setPersistent(true);
            $this->setChanged(false);
        }
    }

    /**
     * Stoesst die Methoden zum Erstellen eines neuen Objektes an
     *
     * @access public
     */
    public function newObjekt()
    {
        $this->doNew();
        $this->afterNew();
    }

    /**
     * Alle Objekt-Properties werden initialisiert
     *
     * @access protected
     *        
     */
    protected abstract function doNew();

    /**
     * Fuehrt Verwaltungsaufgabe nach dem Erstellen eines Objektes durch
     *
     * @access private
     */
    private function afterNew()
    {
        $this->setID(- 1);
        $this->setChangeAt(0);
        $this->setChangeBy("");
        
        $this->setPersistent(false);
        $this->setChanged(true);
    }

    /**
     * Stoesst die Speicherroutine an
     *
     * @access private
     * @param boolean $objektNachSpeichernNeuLaden            
     */
    private function saveNew($objektNachSpeichernNeuLaden)
    {
        $this->doSave();
        $this->afterSave($objektNachSpeichernNeuLaden);
    }

    /**
     * Speichert das Objekt in die Datenbank
     *
     * @access protected
     *        
     */
    protected abstract function doSave();

    /**
     * Fuehrt Verwaltungsaufgabe nach dem Speichern eines Objektes durch
     *
     * @param boolean $objektNachSpeichernNeuLaden            
     * @access private
     */
    private function afterSave($objektNachSpeichernNeuLaden)
    {
        if ($objektNachSpeichernNeuLaden)
            $this->loadLastSavedObjekt();
    }

    /**
     * Laedt das letzte gespeicherte Objekt aus der Datenbank
     *
     * @access protected
     */
    protected abstract function loadLastSavedObjekt();

    /**
     * Stoesst die Updateroutine an
     *
     * @param boolean $objektNachSpeichernNeuLaden            
     */
    private function update($objektNachSpeichernNeuLaden)
    {
        $this->doUpdate();
        $this->afterUpdate($objektNachSpeichernNeuLaden);
    }

    /**
     * Fuehrt ein Update in der Datenbank an dem Datensatz aus
     *
     * @access protected
     */
    protected abstract function doUpdate();

    /**
     * Verwaltungsaufgabe nach dem Updaten eines Objektes
     *
     * @param boolean $objektNachSpeichernNeuLaden            
     * @access private
     */
    private function afterUpdate($objektNachSpeichernNeuLaden)
    {
        if ($objektNachSpeichernNeuLaden) {
            $this->load($this->getID());
        }
    }

    /**
     * Fuehrt interne Methoden aus ob ein neues Objekt gespeichert werden
     * muss oder ein bestehendes Objekt geupdated
     *
     * @access public
     * @param boolean $objektNachSpeichernNeuLaden            
     */
    public function speichern($objektNachSpeichernNeuLaden = true)
    {
        if ($this->isPersistent()) {
            $this->update($objektNachSpeichernNeuLaden);
        } else {
            $this->saveNew($objektNachSpeichernNeuLaden);
        }
    }

    /**
     * Creates the SQL Export String.
     *
     * @access protected
     *        
     */
    protected abstract function doExport();

    public function export()
    {
        return $this->doExport();
    }

    /**
     * Loescht das Objekt aus der Datenbank
     *
     * @access public
     *        
     */
    public abstract function loeschen();

    /**
     * Gibt die ObjektID aus
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return "ID: " . $this->getID();
    }

    /**
     * Gibt die ID des Objektes zurueck
     *
     * @return int / PrimaryKey
     */
    public function getID()
    {
        return $this->iID;
    }

    /**
     * Setzt die ID des Objektes
     *
     * @param int $iID            
     */
    public function setID($iID)
    {
        if ($this->iID != $iID) {
            $this->iID = $iID;
            $this->setChanged(true);
        }
    }

    /**
     * Name des letzten Bearbeiters
     *
     * @access public
     * @return String
     */
    public function getChangeBy()
    {
        return $this->strChangeBy;
    }

    /**
     * Setzt den Namen des letzten Bearbeiters
     *
     * @access public
     * @param String $strChangeBy            
     */
    public function setChangeBy($strChangeBy)
    {
        if ($this->strChangeBy != $strChangeBy) {
            $this->strChangeBy = $strChangeBy;
            $this->setChanged(true);
        }
    }

    /**
     * Datum der letzten Veraenderung
     *
     * @param boolean $formatiert            
     * @return sqlDate / Datum
     */
    public function getChangeAt($formatiert = false)
    {
        if ($formatiert)
            return date("d.m.Y", strtotime($this->dtChangeAt));
        return $this->dtChangeAt;
    }

    /**
     * Setzt das Datum der letzten Veraenderung
     *
     * @param sqlDate $dtChangeAt            
     * @access public
     */
    public function setChangeAt($dtChangeAt = 0)
    {
        if ($dtChangeAt == 0) {
            $this->dtChangeAt = $this->createDateAsString();
        } else {
            $this->dtChangeAt = $dtChangeAt;
        }
    }

    /**
     * Objekt in der Datenbank gespeichert
     *
     * @return boolean
     */
    public function isPersistent()
    {
        return $this->isPersistent;
    }

    /**
     * Gibt an ob das Objekt schon in der Datenbank gespeichert ist
     *
     * @param boolean $boolean            
     * @access public
     */
    public function setPersistent($boolean)
    {
        $this->isPersistent = $boolean;
    }

    /**
     * Speichert ob sich ein Objekt seit dem Laden aus der DB geaendert hat.
     *
     * @access private
     * @return boolean
     */
    public function hasChanged()
    {
        return $this->boObjektHasChanged;
    }

    /**
     * Setzt den internen Merker, ob sich ein Objekt geaendert hat.
     *
     * @access private
     * @param boolean $boolean            
     */
    public function setChanged($boolean)
    {
        // 2016.12.18 - Das Datum soll erst beim speichern gesetzt werden
        // $this->setChangeAt(0);
        $this->boObjektHasChanged = $boolean;
    }

    /**
     * Datum wann der Datensatz erstellt wurde
     *
     * @access public
     * @return sqlDate
     */
    public function getCreatedAt()
    {
        return $this->strCreatedAt;
    }

    /**
     * Setzt das Erstellungsdatum
     *
     * @access public
     * @param string $strChangeAt            
     */
    public function setCreatedAt($strCreatedAt)
    {
        if ($this->strCreatedAt != $strCreatedAt) {
            $this->strCreatedAt = $strCreatedAt;
            $this->setChanged(true);
        }
    }

    public function setSelected($status)
    {
        $this->boSelected = $status;
    }

    public function getSelected()
    {
        return $this->boSelected;
    }

    public function isSelected()
    {
        if ($this->boSelected == "1")
            return true;
        return false;
    }
    
    protected function createDateAsString() {
        return date(self::MYSQL_DATETIME_FORMAT);
    }
}
?>