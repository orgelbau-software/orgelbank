<?php

class Benutzer extends SimpleDatabaseStorageObjekt
{

    private $vorname;

    private $nachname;

    private $benutzername;

    private $passwort;

    private $benutzerlevel;

    private $aktiviert;

    private $geloescht;

    private $eintrittsDatum;

    private $failedLoginCount;

    private $failedLoginLast;

    private $urlaubstage;

    private $urlaubRest;

    private $urlaubAktuell;

    private $stdMontag;

    private $stdDienstag;

    private $stdMittwoch;

    private $stdDonnerstag;

    private $stdFreitag;

    private $stdSamstag;

    private $stdSonntag;

    private $stdGesamt;

    private $stdLohn;

    private $verrechnungsSatz;

    private $zeiterfassung;

    private $sortierung;

    private $demo;
    
    private $email;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "be_id", "benutzer", "be_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setVorname($rs['be_vorname']);
        $this->setNachname($rs['be_nachname']);
        $this->setBenutzername($rs['be_benutzername']);
        $this->setEmail($rs['be_email']);
        $this->setPasswort($rs['be_passwort']);
        $this->setBenutzerlevel($rs['be_benutzerlevel']);
        $this->setAktiviert($rs['be_aktiviert']);
        $this->setGeloescht($rs['be_geloescht']);
        $this->setEintrittsDatum($rs['be_eintrittsdatum']);
        
        $this->setFailedLoginCount($rs['be_failedlogin_count']);
        $this->setFailedLoginLast($rs['be_failedlogin_last']);
        
        $this->setUrlaubstage($rs['be_urlaubstage']);
        $this->setUrlaubRest($rs['be_resturlaub']);
        $this->setUrlaubAktuell($rs['be_urlaub_aktuell']);
        
        $this->setStdMontag($rs['be_std_montag']);
        $this->setStdDienstag($rs['be_std_dienstag']);
        $this->setStdMittwoch($rs['be_std_mittwoch']);
        $this->setStdDonnerstag($rs['be_std_donnerstag']);
        $this->setStdFreitag($rs['be_std_freitag']);
        $this->setStdSamstag($rs['be_std_samstag']);
        $this->setStdSonntag($rs['be_std_sonntag']);
        $this->setStdGesamt($rs['be_std_gesamt']);
        $this->setStdLohn($rs['be_std_lohn']);
        $this->setZeiterfassung($rs['be_zeiterfassung']);
        $this->setVerrechnungsSatz($rs['be_verrechnungssatz']);
        $this->setSortierung($rs['be_sortierung']);
        
        $this->setDemo($rs['be_demo']);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("be_vorname", $this->getVorname());
        $ht->add("be_nachname", $this->getNachname());
        $ht->add("be_benutzername", $this->getBenutzername());
        $ht->add("be_email", $this->getEmail());
        $ht->add("be_passwort", $this->getPasswort());
        $ht->add("be_benutzerlevel", $this->getBenutzerlevel());
        $ht->add("be_aktiviert", $this->getAktiviert());
        $ht->add("be_geloescht", $this->getGeloescht());
        $ht->add("be_eintrittsdatum", $this->getEintrittsDatum());
        
        $ht->add("be_failedlogin_count", $this->getFailedLoginCount());
        $ht->add("be_failedlogin_last", $this->getFailedLoginLast());
        
        $ht->add("be_urlaubstage", $this->getUrlaubstage());
        $ht->add("be_resturlaub", $this->getUrlaubRest());
        $ht->add("be_urlaub_aktuell", $this->getUrlaubAktuell());
        
        $ht->add("be_std_montag", $this->getStdMontag());
        $ht->add("be_std_dienstag", $this->getStdDienstag());
        $ht->add("be_std_mittwoch", $this->getStdMittwoch());
        $ht->add("be_std_donnerstag", $this->getStdDonnerstag());
        $ht->add("be_std_freitag", $this->getStdFreitag());
        $ht->add("be_std_samstag", $this->getStdSamstag());
        $ht->add("be_std_sonntag", $this->getStdSonntag());
        $ht->add("be_std_gesamt", $this->getStdGesamt());
        $ht->add("be_std_lohn", $this->getStdLohn());
        $ht->add("be_zeiterfassung", $this->isZeiterfassung());
        $ht->add("be_verrechnungssatz", $this->getVerrechnungsSatz());
        $ht->add("be_sortierung", $this->getSortierung());
        
        $ht->add("be_demo", $this->getDemo());
        
        return $ht;
    }

    public function loeschen()
    {
        $this->setGeloescht(1);
    }

    public function getBenutzerlevel()
    {
        return $this->benutzerlevel;
    }

    public function getBenutzername()
    {
        return $this->benutzername;
    }
    
    public function getEmail()
    {
        return $this->email;
    }

    public function getNachname()
    {
        return $this->nachname;
    }

    public function getPasswort()
    {
        return $this->passwort;
    }

    public function getVorname()
    {
        return $this->vorname;
    }

    public function getAktiviert()
    {
        return $this->aktiviert;
    }

    public function setBenutzerlevel($benutzerlevel)
    {
        if ($this->benutzerlevel != $benutzerlevel) {
            $this->benutzerlevel = $benutzerlevel;
            $this->setChanged(true);
        }
    }

    public function setBenutzername($benutzernamen)
    {
        if ($this->benutzername != $benutzernamen) {
            $this->benutzername = $benutzernamen;
            $this->setChanged(true);
        }
    }

    public function setEmail($email)
    {
        if ($this->email != $email) {
            $this->email = $email;
            $this->setChanged(true);
        }
    }
    
    public function setNachname($nachname)
    {
        if ($this->nachname != $nachname) {
            $this->nachname = $nachname;
            $this->setChanged(true);
        }
    }

    public function setPasswort($passwort)
    {
        if ($this->passwort != $passwort) {
            $this->passwort = $passwort;
            $this->setChanged(true);
        }
    }

    public function setVorname($vorname)
    {
        if ($this->vorname != $vorname) {
            $this->vorname = $vorname;
            $this->setChanged(true);
        }
    }

    public function setAktiviert($aktiviert)
    {
        if ($this->aktiviert != $aktiviert) {
            $this->aktiviert = $aktiviert;
            $this->setChanged(true);
        }
    }

    public function isAdmin()
    {
        if (10 == $this->benutzerlevel)
            return true;
        return false;
    }

    public function isMonteur()
    {
        if ($this->isAdmin() || 5 == $this->benutzerlevel)
            return true;
        return false;
    }

    public function isAktiviert()
    {
        if (1 == $this->getAktiviert())
            return true;
        return false;
    }

    public function getGeloescht()
    {
        return $this->geloescht;
    }

    public function setGeloescht($geloescht)
    {
        if ($this->geloescht != $geloescht) {
            $this->geloescht = $geloescht;
            $this->setChanged(true);
        }
    }

    public function getStdDienstag()
    {
        return $this->stdDienstag;
    }

    public function getStdDonnerstag()
    {
        return $this->stdDonnerstag;
    }

    public function getStdFreitag()
    {
        return $this->stdFreitag;
    }

    public function getStdGesamt()
    {
        return $this->stdGesamt;
    }

    public function getStdMittwoch()
    {
        return $this->stdMittwoch;
    }

    public function getStdMontag()
    {
        return $this->stdMontag;
    }

    public function getStdSamstag()
    {
        return $this->stdSamstag;
    }

    public function getStdSonntag()
    {
        return $this->stdSonntag;
    }

    public function setStdDienstag($stdDienstag)
    {
        if ($this->stdDienstag != $stdDienstag) {
            $this->stdDienstag = $stdDienstag;
            $this->setChanged(true);
        }
    }

    public function setStdDonnerstag($stdDonnerstag)
    {
        if ($this->stdDonnerstag != $stdDonnerstag) {
            $this->stdDonnerstag = $stdDonnerstag;
            $this->setChanged(true);
        }
    }

    public function setStdFreitag($stdFreitag)
    {
        if ($this->stdFreitag != $stdFreitag) {
            $this->stdFreitag = $stdFreitag;
            $this->setChanged(true);
        }
    }

    public function setStdGesamt($stdGesamt)
    {
        if ($this->stdGesamt != $stdGesamt) {
            $this->stdGesamt = $stdGesamt;
            $this->setChanged(true);
        }
    }

    public function setStdMittwoch($stdMittwoch)
    {
        if ($this->stdMittwoch != $stdMittwoch) {
            $this->stdMittwoch = $stdMittwoch;
            $this->setChanged(true);
        }
    }

    public function setStdMontag($stdMontag)
    {
        if ($this->stdMontag != $stdMontag) {
            $this->stdMontag = $stdMontag;
            $this->setChanged(true);
        }
    }

    public function setStdSamstag($stdSamstag)
    {
        if ($this->stdSamstag != $stdSamstag) {
            $this->stdSamstag = $stdSamstag;
            $this->setChanged(true);
        }
    }

    public function setStdSonntag($stdSonntag)
    {
        if ($this->stdSonntag != $stdSonntag) {
            $this->stdSonntag = $stdSonntag;
            $this->setChanged(true);
        }
    }

    public function getEintrittsDatum($boFormatiert = false)
    {
        if ($boFormatiert)
            return date("d.m.Y", strtotime($this->eintrittsDatum));
        return $this->eintrittsDatum;
    }

    public function setEintrittsDatum($eintrittsDatum)
    {
        if ($this->eintrittsDatum != $eintrittsDatum) {
            $this->eintrittsDatum = $eintrittsDatum;
            $this->setChanged(true);
        }
    }

    public function getUrlaubstage()
    {
        return $this->urlaubstage;
    }

    public function setUrlaubstage($urlaubstage)
    {
        if ($this->urlaubstage != $urlaubstage) {
            $this->urlaubstage = $urlaubstage;
            $this->setChanged(true);
        }
    }

    public function getUrlaubAktuell()
    {
        return $this->urlaubAktuell;
    }

    /**
     * Gibt den Resturlaub in Stunden zurueck
     *
     * @return double Urlaubstage in Stunden
     */
    public function getUrlaubRest()
    {
        return $this->urlaubRest;
    }

    public function setUrlaubAktuell($urlaubAktuell)
    {
        $this->urlaubAktuell = $urlaubAktuell;
        $this->setChanged(true);
    }

    public function setUrlaubRest($urlaubRest)
    {
        $this->setChanged(true);
        $this->urlaubRest = $urlaubRest;
    }

    public function getFailedLoginCount()
    {
        return $this->failedLoginCount;
    }

    public function getFailedLoginLast()
    {
        return $this->failedLoginLast;
    }

    public function setFailedLoginCount($failedLoginCount)
    {
        $this->failedLoginCount = $failedLoginCount;
    }

    public function setFailedLoginLast($failedLoginDate)
    {
        $this->failedLoginLast = $failedLoginDate;
    }

    public function getStdLohn()
    {
        return $this->stdLohn;
    }

    public function setStdLohn($stdLohn)
    {
        $this->stdLohn = $stdLohn;
    }

    public function isZeiterfassung()
    {
        return ($this->zeiterfassung == 1 ? true : false);
    }

    public function setZeiterfassung($zeiterfassung)
    {
        $this->zeiterfassung = ($zeiterfassung == 1 ? true : false);
    }

    public function getVerrechnungsSatz()
    {
        return $this->verrechnungsSatz;
    }

    public function setVerrechnungsSatz($verrechnungsSatz)
    {
        $this->verrechnungsSatz = $verrechnungsSatz;
    }

    public function getSortierung()
    {
        return $this->sortierung;
    }

    public function setSortierung($sortierung)
    {
        $this->sortierung = $sortierung;
    }

    public function isDemo()
    {
        if (1 == $this->getDemo())
            return true;
        return false;
    }

    public function getDemo()
    {
        return $this->demo;
    }

    public function setDemo($pDemo)
    {
        if ($this->demo != $pDemo) {
            $this->demo = $pDemo;
            $this->setChanged(true);
        }
    }
}
?>
