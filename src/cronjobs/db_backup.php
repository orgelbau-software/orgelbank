<?php
$retVal = array();
$retVal['http_status'] = "500";
$retVal['message'] = "";

try {
    include ('Mail.php');
    include ('Mail/mime.php');
    include "../../conf/config.inc.php";
    error_reporting(NULL); // sonst schmeisst die PEAR Bibliothek Fehlermeldungen
                           
    // ####### einstellungen #############################################
    
    $db_name = MYSQL_DB;
    $db_passwd = MYSQL_PASS;
    
    $downloadlink_erstellen = "nein";
    
    $bestaetigungsmail_senden = "ja";
    $bestaetigungsmail_adresse = "stephan@watermeyer.info";
    $bestaetigungsmail_betreff = "[DB BACKUP] " . INSTALLATION_NAME;
    
    $sql_file = BACKUPDIR . "dump_" . $db_name . "_" . date('Ymd_Hi') . ".sql";
    
    if(!is_dir(BACKUPDIR)) {
        if(!mkdir(BACKUPDIR)) {
            throw new Exception("Kann Backupverzeichnis nicht erstellen: ". BACKUPDIR);
        }
    }
    
    // ###################################################################
    
    // ## daten überprüfen
    if ($db_name == "IhreDatenBank" or $db_passwd == "IhrDatenBankPasswort") {
        die("FEHLER: Sie m&uuml;ssen zun&auml;chst Ihre Datenbankdaten im Script eingeben!");
    }
    if (file_exists($sql_file) or file_exists($sql_file . ".gz")) {
        die("FEHLER: Das zu erstellende Dump existiert bereits!");
    }
    
    // # dump erstellen
    exec("mariadb-dump -u $db_name -p'$db_passwd' --quick --allow-keywords --add-drop-table --complete-insert --ignore-table=".$db_name.".rechnung_view --quote-names $db_name >$sql_file");
    if (!file_exists($sql_file) || filesize($sql_file) == 0) {
        throw new Exception("Failed to create database backup. Either maria-dump command failed or size is 0.");
    }
    exec("gzip $sql_file");
    
    // ## größe ermitteln
    $datei = $sql_file . ".gz";
    $size = filesize($datei);
    $i = 0;
    while ($size > 1024) {
        $i ++;
        $size = $size / 1024;
    }
    $fileSizeNames = array(
        " Bytes",
        " KiloBytes",
        " MegaBytes",
        " GigaBytes",
        " TerraBytes"
    );
    $size = round($size, 2);
    $size = str_replace(".", ",", $size);
    $groesse = "$size $fileSizeNames[$i]";
    
    if($groesse > 0) {
        // ## nachricht erstellen
        $message = "Ihr Backup der Datenbank <b>" . $db_name . "</b> wurde durchgeführt.<br>";
        $message .= "Die Größe des erstellten Dumps beträgt <b>" . $groesse . "</b>.<br>";
        
        if ($downloadlink_erstellen == "yes" or $downloadlink_erstellen == "ja" or $downloadlink_erstellen == "1") {
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            $link = str_replace(basename(__FILE__), $datei, $link);
            $message .= "Downloadlink: <a href='" . $link . "'>" . $datei . "</a>";
        }
    } else {
       throw new Exception("Datenbankbackup ist 0 byte: ". $datei);
    }
    
    // # nachricht ausgeben
    $retVal['message'] =  $message;
    
    // ## mail versenden
    $message = str_replace("<br>", "\r\n", $message);
    $message = str_replace("<b>", "", $message);
    $message = str_replace("</b>", "", $message);
    if ($bestaetigungsmail_senden == "yes" or $bestaetigungsmail_senden == "ja" or $bestaetigungsmail_senden == "1") {
        if (! preg_match('/^([a-zA-Z0-9])+([.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-]+)+/', $bestaetigungsmail_adresse)) {
            $retVal['mail'] =  "FEHLER: Mail konnte nicht versendet werden, da die Adresse ung&uuml;ltig ist!";
            $retVal['http_status'] = 500;
        } else {
            $hdrs = array(
                'From' => SUPPORT_MAIL_FROM,
                'Subject' => $bestaetigungsmail_betreff
            );
            $mime = new Mail_mime("\n");
            $mime->setTXTBody($message);
            $mime->addAttachment($datei, 'application/x-zip');
            
            // do not ever try to call these lines in reverse order
            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);
            
            $mail = Mail::factory('mail');
            $mail->send($bestaetigungsmail_adresse, $hdrs, $body);
            if(CUSTOMER_MAIL_TO != "") {
                $retVal['kundenmail'] =  "Best&auml;tigungsmail wurde an den Kunden erfolgreich versandt! ";
                $mail->send(CUSTOMER_MAIL_TO, $hdrs, $body);
            } else {
                $retVal['kundenmail'] =  "Keine Email an den Kunden versendet!";
            }
            $retVal['mail'] =  "Best&auml;tigungsmail wurde erfolgreich an den Administrator versandt!";
            $retVal['http_status'] = 200;
        }
    }
} catch (Throwable $t) {
    $retVal['http_status'] = "501";
    $retVal['message'] = $t->getMessage();
    BenutzerController::doHilfeRufenCronjob($retVal);
}
header('Content-Type: application/json');
http_response_code($retVal['http_status']);
echo json_encode($retVal);