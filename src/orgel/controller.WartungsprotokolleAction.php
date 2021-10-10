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
        
        if ($_POST) {
            $protokoll = new WartungsProtokoll($protokollId);
            if (isset($_FILES['protokoll']) && $_FILES['protokoll']['name'] != "") {
                
                $filetemp = $_FILES['protokoll']['tmp_name'];
                $filename = $_FILES['protokoll']['name'];
                $filesize = $_FILES['protokoll']['size'];
                $fileendung = strtolower(substr(strchr($filename, "."), 1, 5));
                
                $protokollPfad = ROOTDIR . "store/protokolle/" . $protokollId . "_" . $filename;
                
                // Backup
                if (file_exists($protokollPfad)) {
                    copy($protokollPfad, $protokollPfad . "_" . time());
                }
                
                if ($fileendung == "pdf") {
                    copy($filetemp, $protokollPfad);
                } else {
                    $htmlStatus = new HTMLStatus("Die Datei muss eine PDF Datei sein, gefunden wurde eine: " . $fileendung, HTMLStatus::$STATUS_ERROR);
                }
                $protokoll->setDateiname($protokollPfad);
            }
            
            $protokoll->setName($_POST['name']);
            $protokoll->speichern(true);
            $protokollId = $protokoll->getID();
        }
        
        if (isset($_GET['action']) && $_GET['action'] == "edit") {
            $tpl->replace("SubmitValue", "bearbeien");
        } else if (isset($_GET['action']) && $_GET['action'] == "delete") {
            $tpl->replace("SubmitValue", "Löschen");
        } else {
            $tpl->replace("SubmitValue", "Erstellen");
        }
        
        $col = WartungsprotokollUtilities::getWartungsprotokolle();
        $tplProtokollDS = new BufferedTemplate("orgel_wartung_details_ds.tpl", "CSS", "td1", "td2");
        
        if ($col != null && $col->getSize() > 0) {
            foreach ($col as $protokoll) {
                $tplProtokollDS->replace("ProtokollId", $protokoll->getID());
                $tplProtokollDS->replace("Name", $protokoll->getName());
                $tplProtokollDS->replace("Dateiname", $protokoll->getDateiname());
                $tplProtokollDS->replace("Bemerkung", $protokoll->getBemerkung());
                
                $tplProtokollDS->next();
            }
        } else {
            $tplProtokollDS = new BufferedTemplate("orgel_wartungsprotokolle_keine.tpl");
            $tplProtokollDS->next();
        }
        $tpl->replace("Protokolle", $tplProtokollDS->getOutput());
        
        $tpl->replace("Name", "XXX");
        $tpl->replace("Bemerkung", "YYY");
        $tpl->replace("Dateiname", "ZZZ");
        
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