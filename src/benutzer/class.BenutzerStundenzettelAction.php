<?php
class BenutzerStundenzettelAction extends MitarbeiterStundenzettelAction {
    
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