<?php

class PageApp
{

    private $htApplication;

    private $htControllers;

    public function __construct()
    {
        $this->htApplication = new HashTable();
    }

    public function addController(PageController $pc)
    {
        if (null == $this->htControllers)
            $this->htControllers = new HashTable();
        
        $this->htControllers->add($pc->getIdentifier(), $pc);
    }

    /**
     * Überprüft ob eine Seite angefordert wurde, ob PAGE und DO Zahlen sind, ob PAGE und DO in gültigen Bereich liegen, ob ein Benutzer angemeldet ist, ob der Benutzer einen ausreichenden Level hat
     *
     * @return boolean TRUE wenn Recht ausreichend
     */
    public function hatRecht()
    {
        if (! $this->isPageRequested()) {
            return false;
        }
        
        $page = $this->getRequestedPageId();
        $do = $this->getRequestedAction();
        
        if ($page <= 0 || $do <= 0 || $page > 8 || $do > 250) {
            Log::debug("GET-Param 'page' oder 'do' nicht gueltig. page=" . $page . ", do=" . $do . " # kein Recht");
            return false;
        }
        
        global $webUser;
        if ($webUser == null || $webUser->getBenutzer() == null) {
            Log::debug("global webUser oder getBenutzer ist NULL # kein Recht");
            return false;
        }
        
        $c = $this->htControllers->getValueOf($page);
        $toCheck = $c->getChild($do);
        
        if (null == $toCheck) {
            throw new Exception("DO-Action nicht in PageApp definiert: page=" . $page . ", do=" . $do);
        }
        
        Log::debug("Pruefe Recht, IST: " . $webUser->getBenutzer()->getBenutzerlevel() . ", MUSS: " . $toCheck->getMinUserLvl());
        return $webUser->getBenutzer()->getBenutzerlevel() >= $toCheck->getMinUserLvl();
    }

    public function isPageRequested()
    {
        if ($this->getRequestedPageId() <= 0 || $this->getRequestedAction() <= 0) {
            Log::debug("Keine Seite angefordert: GET-Param 'page' oder 'do' nicht gesetzt");
            return false;
        }
        return true;
    }

    /**
     * Zeigt die angeforderte Seite an.
     * Bezieht sich auf $_GET['page'] und $_GET['do'].
     * Führt implizit eine Rechteprüfung durch
     *
     * @return boolean TRUE wenn Anzeigen möglich
     */
    public function show()
    {
        if ($this->hatRecht()) {
            $page = $this->getRequestedPageId();
            $do = $this->getRequestedAction();
            
            $c = $this->htControllers->getValueOf($page);
            $toCheck = $c->getChild($do);
            
            $functionName = $c->getName() . "::" . $toCheck->getName();
            return $this->showPage($page, $do);
        }
        Log::debug("Gewuenschte View kann nicht angezeigt werden. Keine Berechtigung.");
        return false;
    }

    /**
     * Ruft aus der Applikation den entsprechenden Controller und die Funktion auf.
     * Um die über die URL angeforderte Seite anzuzeigen, kann einfach show() verwendet werden.
     *
     * @param int $controllerId            
     * @param int $functionId            
     * @return boolean
     */
    public function showPage($controllerId, $functionId)
    {
        $c = $this->htControllers->getValueOf($controllerId);
        $toCheck = $c->getChild($functionId);
        SeitenStatistik::count("index.php?page=" . $controllerId . "&do=" . $functionId, $c->getName() . "::" . $toCheck->getName());
        call_user_func(array(
            $c->getName(),
            $toCheck->getName()
        ));
        return true;
    }

    public function getRequestedPageId()
    {
        $page = - 1;
        if (isset($_GET['page'])) {
            $page = intval($_GET['page']);
        }
        return $page;
    }

    public function getRequestedAction()
    {
        $do = - 1;
        if (isset($_GET['do'])) {
            $do = intval($_GET['do']);
        }
        return $do;
    }
}
?>