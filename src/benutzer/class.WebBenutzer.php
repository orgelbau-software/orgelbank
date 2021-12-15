<?php

class WebBenutzer
{

    private $benutzer;

    private $isAuthed;

    private $errorCount;

    private $errorTXT;

    public function __construct()
    {
        $this->benutzer = new Benutzer();
        $this->isAuthed = false;
    }

    public function getID()
    {
        return $this->benutzer->getID();
    }

    public function getBenutzername()
    {
        return ($this->benutzer == null ? "" : $this->benutzer->getBenutzername());
    }

    public function setBenutzername($benutzername)
    {
        $this->benutzer->setBenutzername($benutzername);
        $this->isAuthed = false;
    }

    public function getPasswort()
    {
        return $this->benutzer->getPasswort();
    }

    public function setPasswort($passwort)
    {
        $this->benutzer->setPasswort($passwort);
        $this->isAuthed = false;
    }

    public function isAktiviert()
    {
        return $this->benutzer->isAktiviert();
    }

    public function setAktiviert($i)
    {
        $this->benutzer->setAktiviert($i);
    }

    public function deaktivieren()
    {
        $this->benutzer->setAktiviert(1);
        $this->benutzer->speichern(); // Siehe Guarding Clause in Main, auto commit!
    }

    public function login()
    {
        $retVal = false;
        if (BenutzerUtilities::exists($this->benutzer->getBenutzername()) == true) {
            
            if (BenutzerUtilities::authorisiereBenutzerdaten($this->getBenutzername(), $this->getPasswort())) {
                $retVal = true;
                
                // Benutzer erst nach Authorisierungsprüfung laden
                $this->benutzer = BenutzerUtilities::loadByBenutzername($this->benutzer->getBenutzername());
                
                // Benutzerdaten in Session speichern
                $this->initSessionData();
                
                // Wenn der letzte Fehlgeschlagene Login länger als 3 Tage her ist, Konto langsam wieder freischalten
                if ($this->benutzer->getFailedLoginCount() >= (ConstantLoader::getMaxFailedLogins() - 1) && strtotime("- 3 day") > strtotime($this->benutzer->getFailedLoginLast())) {
                    $this->benutzer->setFailedLoginCount(($this->benutzer->getFailedLoginCount() - 3));
                    // Verhindert, dass ein negativer Wert geschrieben wird und so ein "Polster" aufgebaut wird
                    if ($this->benutzer->getFailedLoginCount() < 0)
                        $this->benutzer->setFailedLoginCount(0);
                    $this->benutzer->speichern();
                } else {
                    // Nach 10 Tagen ohne fehlerhafte Logins kann das Konto komplett wieder regeneriert werden
                    if (($this->benutzer->getFailedLoginCount() > 0) && strtotime("- 10 day") > strtotime($this->benutzer->getFailedLoginLast())) {
                        $this->benutzer->setFailedLoginCount(0);
                        $this->benutzer->speichern();
                    }
                }
            } else {
                $this->benutzer = BenutzerUtilities::loadByBenutzername($this->benutzer->getBenutzername());
                $this->benutzer->setFailedLoginCount(($this->benutzer->getFailedLoginCount() + 1));
                $this->benutzer->setFailedLoginLast(date("Y-m-d H:i:s"));
                
                if ($this->benutzer->getFailedLoginCount() >= ConstantLoader::getMaxFailedLogins()) {
                    $this->benutzer->setAktiviert(0);
                    $this->errorTXT = "Ihr Konto wurde aus Sicherheitsgründen gesperrt. Wenden Sie sich an den Systemadministrator.";
                    SupportMail::send("Benutzer wurde gesperrt: " . $this->benutzer->getBenutzername(), "");
                }
                $this->benutzer->speichern();
                $this->benutzer = null;
            }
        } else {
            SupportMail::send("Versuch mit Loginname: " . $this->benutzer->getBenutzername(), "");
        }
        return $retVal;
    }

    public function isAuthed()
    {
        return BenutzerUtilities::authorisiereBenutzerdaten($this->getBenutzername(), $this->benutzer->getPasswort());
    }

    public function validateSession()
    {
        $consistentSession = true;
        if (! isset($_SESSION['user'])) {
            $consistentSession = false;
            Log::debug("user not set");
        }
        if (! isset($_SESSION['user']['id'])) {
            $consistentSession = false;
            Log::debug("user->id not set");
        }
        if (! isset($_SESSION['user']['benutzername'])) {
            $consistentSession = false;
            Log::debug("user->benutzername not set");
        }
        if (! isset($_SESSION['user']['passwort'])) {
            $consistentSession = false;
            Log::debug("user->passwort not set");
        }
        
        if ($consistentSession == true) {
            $this->initBean();
        }
        
        return $consistentSession;
    }

    public function isAdmin()
    {
        return $this->benutzer->isAdmin();
    }

    public function isMonteur()
    {
        return $this->benutzer->isMonteur();
    }

    public function logout()
    {
        $this->benutzer = null;
        session_destroy();
    }

    public function initSessionData()
    {
        $_SESSION['user']['id'] = $this->getID();
        $_SESSION['user']['benutzername'] = $this->getBenutzername();
        $_SESSION['user']['passwort'] = $this->getPasswort();
        
        $_SESSION['request']['lastaction'] = time();
    }

    public function initBean()
    {
        $this->setBenutzername($_SESSION['user']['benutzername']);
        $this->setPasswort($_SESSION['user']['passwort']);
    }

    public function getCreatedAt()
    {
        return $this->benutzer->getCreatedAt();
    }

    public function getBenutzer()
    {
        return $this->benutzer;
    }

    public function isLoginExpired()
    {
        if (isset($_SESSION['request']['lastaction']) && $_SESSION['request']['lastaction'] >= strtotime("+" . ConstantLoader::getMaximumIdleTime() . " Minute")) {
            return true;
        }
        return false;
    }

    public function getErrorMessage()
    {
        return $this->errorTXT;
    }
}
?>
