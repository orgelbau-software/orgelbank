<?php
class BenutzerStundenzettelAction extends MitarbeiterStundenzettelAction {
    
    /**
     * 
     * @param int $pUserId 
     * @param string $pJahr 
     * @return void 
     */
    public function __construct($pUserId, $pJahr) {
        $this->benutzerId = $pUserId;
        $this->jahr = $pJahr;
    }
    
    public function validateGetRequest() {
        return true;
    }
    
    public function prepareGet() {
        return;
    }
}