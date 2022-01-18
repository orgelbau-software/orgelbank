<?php

class OrgelBildAction implements GetRequestHandler, PostRequestHandler, PostRequestValidator
{

    private $oid;

    private $operationStatusMsg;

    public function preparePost()
    {
        $this->oid = intval($_POST['o_id']);
        $this->action = "upload";
    }

    private function executeDelete()
    {
        $tplStatus = new HTMLStatus();
        $bildPfad = ROOTDIR . "store/orgelpics/" . $_GET['oid'] . "_" . $_GET['picid'] . ".jpg";
        $thumbPfad = ROOTDIR . "store/orgelpics/thumbs/" . $_GET['oid'] . "_" . $_GET['picid'] . ".jpg";
        
        if (file_exists($bildPfad)) {
            unlink($bildPfad);
            if (file_exists($bildPfad))
                unlink($bildPfad);
            
            if (file_exists($thumbPfad))
                unlink($thumbPfad);
            
            $this->operationStatusMsg = new HTMLStatus("Bild erfolgreich gel&ouml;scht.", 2);
        } else {
            $this->operationStatusMsg = new HTMLStatus("Bild nicht gel&ouml;scht.", 1);
        }
    }

    public function executePost()
    {
        if (! is_dir(ORGELBILD_BILD_PFAD)) {
            mkdir(ORGELBILD_BILD_PFAD) || die("Kann das Verzeichnis " . ORGELBILD_BILD_PFAD . " nicht erstellen");
        }
        if (! is_dir(ORGELBILD_THUMB_PFAD)) {
            mkdir(ORGELBILD_THUMB_PFAD) || die("Kann das Verzeichnis " . ORGELBILD_THUMB_PFAD . " nicht erstellen");
        }
        
        $zielId = 0;
        if (! file_exists(ORGELBILD_BILD_PFAD . $this->oid . "_3.jpg"))
            $zielId = 3;
        if (! file_exists(ORGELBILD_BILD_PFAD . $this->oid . "_2.jpg"))
            $zielId = 2;
        if (! file_exists(ORGELBILD_BILD_PFAD . $this->oid . "_1.jpg"))
            $zielId = 1;
        
        if ($zielId > 0) {
            $bildPfad =  ORGELBILD_BILD_PFAD . $this->oid . "_" . $zielId . ".jpg";
            $thumbPfad =  ORGELBILD_THUMB_PFAD . $this->oid . "_" . $zielId . ".jpg";
            
            $filetemp = $_FILES['probe']['tmp_name'];
            $filename = $_FILES['probe']['name'];
            $filesize = $_FILES['probe']['size'];
            $fileendung = strtolower(substr(strchr($filename, "."), 1, 5));
            
            if (! file_exists($filetemp)) {
                $this->operationStatusMsg = new HTMLStatus("Bitte w&auml;hlen Sie ein Bild aus.", 1);
            } elseif ($filesize > 10240000) {
                $this->operationStatusMsg = new HTMLStatus("Die maximale Dateigr&ouml;&szlig;e betr&auml;gt 10 MB.", 1);
            } else {
                if (file_exists($bildPfad))
                    unlink($bildPfad);
                
                if (file_exists($thumbPfad))
                    unlink($thumbPfad);
                
                $imagesize = getimagesize($filetemp);
                if ($imagesize['0'] > 4000 || $imagesize['1'] > 4000 || strtolower($fileendung) != "jpg") {
                    $this->operationStatusMsg = new HTMLStatus("Die Datei entspricht nicht den Vorgaben von einem JPG mit max 4000 x 4000 Pixeln.", 1);
                } else {
                    copy($filetemp, $bildPfad);
                    CreateImage(600, $bildPfad, $bildPfad, 0);
                    // CreateImage($zielId > 1 ? 100 : 300, $bildPfad, $thumbPfad, 0);
                    CreateImage(100, $bildPfad, $thumbPfad, 0);
                    $this->operationStatusMsg = new HTMLStatus("Bild erfolgreich hochgeladen", 2);
                }
            }
        } else {
            $this->operationStatusMsg = new HTMLStatus("Sie m&uuml;ssen erst ein Bild l&ouml;schen, bevor Sie ein neues speichern k&ouml;nnen.", 1);
        }
        return $this->executeGet();
    }

    public function validatePostRequest()
    {
        return isset($_POST['o_id']) && intval($_POST['o_id']) > 0;
    }

    public function handleInvalidPost()
    {
        $this->handleInvalidGet();
    }

    public function validateGetRequest()
    {
        if (isset($_GET['oid']) == false) {
            return false;
        }
        if (isset($_GET['action']) == false) {
            return false;
        }
        if (intval($_GET['oid']) > 0 == false) {
            return false;
        }
        if ($_GET['action'] != "show" && $_GET['action'] != "delete") {
            return false;
        }
        return true;
    }

    public function handleInvalidGet()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setNachricht("Keine gültige OrgelID oder Action übergeben");
        $htmlStatus->setLink("index.php?page=1&do=23");
        return $htmlStatus;
    }

    public function prepareGet()
    {
        $this->oid = intval($_GET['oid']);
        $this->action = $_GET['action'];
    }

    public function executeGet()
    {
        if ($this->action == "delete") {
            $this->executeDelete();
        }
        
        $tpl = new Template("orgel_bild.tpl");
        $tpl->replace("OID", $this->oid);
        $oOrgel = new Orgel($this->oid);
        $oGemeinde = new Gemeinde($oOrgel->getGemeindeId());
        
        // Bild
        $iBildCounter = 0;
        $tplOrgelBilder = new BufferedTemplate("orgel_details_orgelbild.tpl");
        for ($i = 1; $i <= 3; $i ++) {
            $bildPfad = ORGELBILD_BILD_PFAD . $oOrgel->getID() . "_" . $i . ".jpg";
            $thumbPfad = ORGELBILD_THUMB_PFAD . $oOrgel->getID() . "_" . $i . ".jpg";
            if (file_exists($bildPfad)) {
                $tplOrgelBilder->replace("PicID", $i);
                $tplOrgelBilder->replace("OID", $oOrgel->getID());
                $tplOrgelBilder->replace("GemeindeNamen", $oGemeinde->getKirche());
                
                if (file_exists($thumbPfad)) {
                    $imagesize = getimagesize("store/orgelpics/thumbs/" . $oOrgel->getID() . "_" . $i . ".jpg");
                    $width = $imagesize[1];
                    if ($imagesize[0] > $imagesize[1]) {
                        $width = $imagesize[1];
                    }
                } else {
                    $width = 0;
                }
                
                $tplOrgelBilder->replace("Bildname", $oOrgel->getID());
                $tplOrgelBilder->replace("BildBreite", $width);
                $tplOrgelBilder->next();
                $iBildCounter ++;
            }
        }
        
        if ($this->operationStatusMsg != null) {
            $tpl->replace("Status", $this->operationStatusMsg->getOutput());
        }
        $tpl->replace("OrgelBilder", $tplOrgelBilder->getOutput());
        $tpl->replace("AnzahlOrgelBilder", $iBildCounter);
        return $tpl;
    }

    public function __toString()
    {
        return "OrgelBildAction.class";
    }
}
?>