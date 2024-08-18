<?php

class FehlerEintrag
{

    public $clzz = " ";

    public $function = " ";

    public $line = " ";

    public $param = " ";

    public $callBy = " ";

    public $file = " ";

    public function __toString()
    {
        return $this->clzz . $this->callBy . $this->function . " " . $this->line . " [" . print_r($this->param, true) . "]";
    }
}

class ExceptionHandler
{

    protected $code;

    protected $text;

    protected $datei;

    protected $zeile;

    protected $kontext;

    public function __construct($code, $text, $datei, $zeile, $kontext)
    {
        $this->code = $code;
        $this->text = $text;
        $this->datei = $datei;
        $this->zeile = $zeile;
        $this->kontext = $kontext;
    }

    public static function handle(Throwable $e)
    {
        if (error_reporting() < $e->getCode())
            return;
        $oHandler = new ExceptionHandler($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTrace());
        $oHandler->handleError();
        die("EXIT ON EXCEPTION");
    }

    private function handleError()
    {
        $this->ausgabe();
    }

    public function ausgabe()
    {
        global $webUser;
        
        $s = "";
        $mailContent = "";
        $s .= "<div class=\"phperror\">";
        $s .= "	<h1>" . $this->text . "</h1>";
        $mailContent .= "Fehlermeldung: " . $this->text . "\r\n";
        $s .= "	<table>";
        $s .= "		<tr>";
        $s .= "			<th>Datei:</th>";
        $s .= "			<td>" . $this->datei . "</td>";
        $mailContent .= "Datei: " . $this->datei . "\r\n";
        $s .= "		</tr>";
        $s .= "		<tr>";
        $s .= "			<th>Zeile:</th>";
        $s .= "			<td>" . $this->zeile . "</td>";
        $mailContent .= "Zeile: " . $this->zeile . "\r\n";
        $s .= "		</tr>";
        $s .= "		<tr>";
        $s .= "			<th>Level:</th>";
        $s .= "			<td>" . $this->code . "</td>";
        $mailContent .= "Code: " . $this->code . "\r\n";
        $s .= "		</tr>				";
        $s .= "	</table>";
        $s .= "	<div class=\"kontext\">";
        $s .= "<h3>Trace:</h3>";
        $s .= "		<ol>";
        $r = $this->handleKontext($this->kontext);
        foreach ($r as $fe) {
            if (is_array($fe->clzz)) {
                $theClass = "unknown";
            } else {
                $theClass = $fe->clzz;
            }
            
            if (is_array($fe->function)) {
                $theFunction = "unknown";
            } else {
                $theFunction = $fe->function;
            }
            $s .= "<li style='padding-top: 10px;'>" . $fe->file . "</li>";
            $mailContent .= "\t\t" . $fe->file . "\r\n";
            $s .= "		<ul>";
            $s .= "			<li>Klasse: <b>" . $theClass . "</b></li>";
            $s .= "			<li>Funktion: <b>" . $theFunction . "</b></li>";
            $s .= "			<li>Zeile: <b>" . $fe->line . "</b></li>";
            
            $mailContent .= "\t\t\tKlasse: " . $theClass . "\r\n";
            $mailContent .= "\t\t\tFunktion: " . $theFunction . "\r\n";
            $mailContent .= "\t\t\tZeile: " . $fe->line . "\r\n";
            
            if (is_array($fe->param)) {
                $s .= "			<li>Parameter:</li>";
                $s .= "				<ol>";
                $mailContent .= "\t\t\tParameter:\r\n";
                foreach ($fe->param as $p) {
                    if (is_string($p)) {
                        $s .= "<li>-->" . $p . "</li>";
                        $mailContent .= "\t\t\t\t-->: " . $p . "\r\n";
                    }
                }
                $s .= "				</ol>";
            } else {
                $s .= "			<li>Parameter: <b>Keine</b></li>";
            }
            $s .= "		</ul>";
        }
        $s .= "		</ol>";
        $s .= "<h3>User:</h3>";
        $s .= "		<pre>";
        if ($webUser != null && is_object($webUser)) {
            $s .= $webUser->getBenutzername();
            $mailContent .= "Benutzer: " . $webUser->getBenutzername() . "\r\n";
        }
        $s .= "		</pre>";
        $s .= "	</div>";
        $s .= "</div>";
        echo $s;
        
        if (SUPPORT_MAIL_ENABLED && $this->isOnline()) {
            $x = "<div class=\"mailstatus\">SupportMail an den Systemadministrator: ";
            if (SupportMail::send($this->text . ":" . $this->zeile, $mailContent)) {
                $x .= "GESENDET";
            } else {
                $x .= "NICHT GESENDET";
            }
            $x .= "</div>";
            echo $x;
        }
    }

    private function handleKontext($kontext)
    {
        $retVal = array();
        foreach ($kontext as $file) {
            $iCounter = 0;
            $fe = new FehlerEintrag();
            foreach ($file as $entry) {
                switch ($iCounter) {
                    case 0:
                        $fe->file = $entry;
                        break;
                    case 1:
                        $fe->line = $entry;
                        break;
                    case 2:
                        $fe->function = $entry;
                        break;
                    case 3:
                        $fe->clzz = $entry;
                        break;
                    case 4:
                        $fe->callBy = $entry;
                        break;
                    case 5:
                        $fe->param = $entry;
                        break;
                }
                $iCounter ++;
            }
            $retVal[] = $fe;
        }
        return $retVal;
    }

    private function isOnline()
    {
        if($_SERVER['REMOTE_ADDR'] == "::1") {
            return false;
        }

        if($_SERVER['REMOTE_ADDR'] == "127.0.0.1") {
            return false;
        }
        
        return true;
    }
}

?>