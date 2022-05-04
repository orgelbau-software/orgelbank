<?php

class WartungsprotokolleAction implements PostRequestHandler, GetRequestHandler
{

    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        return $this->executePost();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::executePost()
     */
    public function executePost()
    {
        $tpl = new Template("orgel_wartungsprotokolle_verwalten.tpl");
        
        $protokollId = 0;
        if (isset($_GET['wpid'])) {
            $protokollId = $_GET['wpid'];
        } else if (isset($_POST['wpid'])) {
            $protokollId = $_POST['wpid'];
        } else {
            $protokollId = 0;
        }
        $tpl->replace("protokollId", $protokollId);
        
        $htmlStatus = null;
        
        $protokoll = new Wartungsprotokoll($protokollId);
        if ($_POST) {
            
            // Lade das Protokoll
            $protokoll = new Wartungsprotokoll($protokollId);
            
            if ($_POST['submit'] == "Erstellen" || $_POST['submit'] == "Bearbeiten") {

                // Datei nur ggf. updaten
                $dateiSpeichern = false;
                $protokoll->setName($_POST['name']);
                $protokoll->setBemerkung($_POST['bemerkung']);
                $protokoll->speichern(true);
                $protokollId = $protokoll->getID(); // fuer neue Protokolle brauchen wir noch die ID.
                
                if (isset($_FILES['protokoll']) && $_FILES['protokoll']['name'] != "") {
                    $filename = $_FILES['protokoll']['name'];
                    $fileendung = strtolower(substr($filename, strrpos($filename, ".") + 1));
                    if ($fileendung == "pdf") {
                        $dateiSpeichern = true;
                    } else {
                        $htmlStatus = new HTMLStatus("Die Datei muss eine PDF Datei sein, gefunden wurde eine: " . $fileendung, HTMLStatus::$STATUS_ERROR);
                    }
                }
                
                $protokoll->speichern(true);
                $protokollId = $protokoll->getID(); // fuer neue Protokolle brauchen wir noch die ID.
                
                if ($dateiSpeichern) {
                    
                    $filetemp = $_FILES['protokoll']['tmp_name'];
                    $filename = $_FILES['protokoll']['name'];
                    $filesize = $_FILES['protokoll']['size'];
                    $fileendung = strtolower(substr($filename, strrpos($filename, ".") + 1));
                    
                    $filename = str_replace(" ", "_", $filename);
                    
                    $relativerPfad = "store/protokolle/" . $protokollId . "_" . $filename;
                    $protokollPfad = ROOTDIR . $relativerPfad;
                    
                    // Backup
                    if (file_exists($protokollPfad)) {
                        copy($protokollPfad, $protokollPfad . "_" . time());
                    }
                    if(copy($filetemp, $protokollPfad)) {
                        $protokoll->setDateiname($relativerPfad);
                        $protokoll->speichern(true);
                        
                        $htmlStatus = new HTMLStatus();
                        $htmlStatus->setText("Wartungsprotokoll gespeichert: " . $protokollPfad);
                    } else {
                        $htmlStatus = new HTMLStatus();
                        $htmlStatus->setText("Wartungsprotokoll konnte nicht gespeichert werden.");
                    }
                }
                
            } else if ($_POST['submit'] == "Löschen") {
                WartungsprotokollUtilities::deleteWartungsprotokoll($protokollId);
                $htmlStatus = new HTMLStatus();
                $htmlStatus->setText("Wartungsprotokoll erfolgreich gel&ouml;scht.");
            } else {
                $htmlStatus = new HTMLStatus();
                $htmlStatus->setText("Unbekannte Action: " .$_POST['submit']);
            }
        }
        
        if (isset($_GET['action']) && $_GET['action'] == "edit") {
            $tpl->replace("SubmitValue", "Bearbeiten");
        } else if (isset($_GET['action']) && $_GET['action'] == "delete") {
            $tpl->replace("SubmitValue", "Löschen");
        } else {
            $tpl->replace("SubmitValue", "Erstellen");
        }
        
        $col = WartungsprotokollUtilities::getWartungsprotokolle();
        $tplProtokollDS = new BufferedTemplate("orgel_wartungsprotokolle_ds.tpl", "CSS", "td1", "td2");
        
        if ($col != null && $col->getSize() > 0) {
            foreach ($col as $currProtokoll) {
                $tplProtokollDS->replace("ProtokollId", $currProtokoll->getID());
                $tplProtokollDS->replace("Name", $currProtokoll->getName());
                $tplProtokollDS->replace("Dateiname", $currProtokoll->getDateiname());
                $tplProtokollDS->replace("Bemerkung", $currProtokoll->getBemerkung());
                $tplProtokollDS->next();
            }
        } else {
            $tplProtokollDS = new BufferedTemplate("orgel_wartungsprotokolle_keine.tpl");
            $tplProtokollDS->next();
        }
        $tpl->replace("Protokolle", $tplProtokollDS->getOutput());
        
        $tpl->replace("Name", $protokoll->getName());
        $tpl->replace("Bemerkung", $protokoll->getBemerkung());
        $tpl->replace("Dateiname", $protokoll->getDateiname());
        
        if ($protokollId > 0) {
            $tpl->replace("ProtokollId", $protokollId);
        }
        $tpl->replace("ProtokollId", "");
        
        // HTML Status
        if ($htmlStatus != null) {
            $tpl->replace("HTMLStatus", $htmlStatus->getOutput());
        }
        
        return $tpl;
    }
}