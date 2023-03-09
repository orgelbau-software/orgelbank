<?php

class AnsprechpartnerSpeichern implements PostRequestHandler, PostRequestValidator
{

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {}

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::validatePostRequest()
     */
    public function validatePostRequest()
    {
        if (! $_POST || ! isset($_POST['aid'])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::handleInvalidPost()
     */
    public function handleInvalidPost()
    {
        return new HTMLStatus("Kein POST Request oder AID fehlt.");
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return Template
     */
    public function executePost()
    {
        if ($_POST['aid'] == 0) {
            $oAnsprechpartner = new Ansprechpartner();
        } else {
            $oAnsprechpartner = new Ansprechpartner(intval($_POST['aid']));
        }
        
        if (isset($_POST['name']) && $_POST['name'] != "") {
            Utilities::escapePost();
            $oAnsprechpartner->setAnrede($_POST['anrede']);
            
            // Firmendaten aendern, nicht Ansprechpartner bearbeiten
            if (isset($_POST['firma'])) {
                $oAnsprechpartner->setFirma($_POST['firma']);
            }
            $oAnsprechpartner->setTitel($_POST['titel']);
            $oAnsprechpartner->setVorname($_POST['vorname']);
            $oAnsprechpartner->setFunktion($_POST['funktion']);
            $oAnsprechpartner->setNachname($_POST['name']);
            $oAnsprechpartner->getAdresse()->setType(Adresse::TYPE_ANSPRECHPARTNER);
            $oAnsprechpartner->getAdresse()->setStrasse($_POST['strasse']);
            $oAnsprechpartner->getAdresse()->setHausnummer($_POST['hausnummer']);
            $oAnsprechpartner->getAdresse()->setPLZ($_POST['plz']);
            $oAnsprechpartner->getAdresse()->setOrt($_POST['ort']);
            if (isset($_POST['land'])) {
                $oAnsprechpartner->getAdresse()->setLand($_POST['land']);
            }
            $oAnsprechpartner->setTelefon($_POST['telefon']);
            $oAnsprechpartner->setFax($_POST['fax']);
            $oAnsprechpartner->setMobil($_POST['mobil']);
            $oAnsprechpartner->setEmail($_POST['email']);
            $oAnsprechpartner->setBemerkung($_POST['bemerkung']);
            $oAnsprechpartner->setAktiv(1);
            if (isset($_POST['webseite'])) {
                $webseite = $_POST['webseite'];
                if ("" != $webseite && strpos($webseite, "http") !== 0) {
                    $webseite = "http://" . $webseite;
                }
                $oAnsprechpartner->setWebseite($webseite);
            }
            
            // war auskommentiert, wegen Speichern der Firmendaten wieder einkommentiert
            $oAnsprechpartner->setAndere($_POST['andere']);
            
            $oAnsprechpartner->speichern(true);
            if (isset($_POST['gid']) && intval($_POST['gid']) != 0) {
                AnsprechpartnerController::addAnsprechpartnerZuGemeinde($oAnsprechpartner->getID(), $_POST['gid']);
            }
            
            // == 1 bedeutet die Änderung der Firmenanschrift des Inhabers. Also kein Ansprechpartner.
            if ($oAnsprechpartner->getID() === 1) {
                
                $geocoder = new OrgelbankGoogleMapsGeocoder();
                $geocoder->setAddress($oAnsprechpartner->getAdresse()
                    ->getFormattedAdress());
                $result = $geocoder->geocode();
                if (IGeolocationConstants::OK == $result) {
                    $oAnsprechpartner->getAdresse()->setLat($geocoder->getAdresse()
                        ->getLat());
                    $oAnsprechpartner->getAdresse()->setLng($geocoder->getAdresse()
                        ->getLng());
                    $oAnsprechpartner->getAdresse()->setGeoStatus(IGeolocationConstants::OK);
                    $oAnsprechpartner->getAdresse()->speichern(true);
                    
                    $htmlRedirect = new HTMLRedirect("Ansprechpartner wurde gespeichert", "index.php?page=7&do=121");
                } else {
                    $htmlRedirect = new HTMLRedirect("Ansprechpartner wurde gespeichert aber die Adressdaten konnten nicht ermittelt werden. " . Constant::getGeoStatusUserMessage($result, "Firmenanschrift"), "index.php?page=7&do=121");
                }
            } else {
                $nachnameAnfangsbuchstabe = substr($oAnsprechpartner->getNachname(), 0, 1);
                if (strlen($nachnameAnfangsbuchstabe) != 1) {
                    $nachnameAnfangsbuchstabe = "A";
                }
                $link = "index.php?page=3&do=40&aid=" . $oAnsprechpartner->getID() . "&a=" . $nachnameAnfangsbuchstabe;
                if (isset($_SESSION['request']['oid'])) {
                    $link = "index.php?page=2&do=28&oid=" . $_SESSION['request']['oid'];
                    unset($_SESSION['request']['oid']);
                }
                $htmlRedirect = new HTMLRedirect("Ansprechpartner wurde gespeichert", $link);
            }
        } else {
            $htmlStatus = new HTMLStatus("Bitte geben Sie mindestens einen <strong>Nachnamen</strong> an. Ansprechpartner wurde nicht gespeichert", 1);
            $htmlRedirect = new HTMLRedirect($htmlStatus->getOutput(), "index.php?page=3&do=40", ConstantLoader::getDefaultRedirectSecondsFalse());
        }
        
        return $htmlRedirect;
    }
}