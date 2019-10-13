<?php

class ErrorHandler
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

    public static function handle($code, $text, $datei, $zeile, $kontext)
    {
        if (error_reporting() < $code)
            return;
        $oHandler = new ErrorHandler($code, $text, $datei, $zeile, $kontext);
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
        $mail = "";
        $s .= "<div class=\"phperror\">";
        $s .= "	<h1>" . $this->text . "</h1>";
        $mail .= "Fehlermeldung: " . $this->text . "\r\n";
        $s .= "	<table>";
        $s .= "		<tr>";
        $s .= "			<th>Datei:</th>";
        $s .= "			<td>" . $this->datei . "</td>";
        $mail .= "Datei: " . $this->datei . "\r\n";
        $s .= "		</tr>";
        $s .= "		<tr>";
        $s .= "			<th>Zeile:</th>";
        $s .= "			<td>" . $this->zeile . "</td>";
        $mail .= "Zeile: " . $this->zeile . "\r\n";
        $s .= "		</tr>";
        $s .= "		<tr>";
        $s .= "			<th>Level:</th>";
        $s .= "			<td>" . $this->code . "</td>";
        $mail .= "Code: " . $this->code . "\r\n";
        $s .= "		</tr>				";
        $s .= "	</table>";
        $s .= "	<div class=\"kontext\">";
        $s .= "<h3>Kontext:</h3>";
        $s .= "		<pre>";
        
        $x = print_r($this->kontext, true);
        $x = htmlspecialchars($x);
        
        $s .= $x;
        $mail .= "Kontext:\r\n\r\n";
        $mail .= $x . "\r\n";
        $s .= "		</pre>";
        
        $s .= "<ol>";
        $trace = debug_backtrace();
        foreach ($trace as $fe) {
            
            if (isset($fe['file']) && strpos($fe['file'], "ErrorHandler.php") > 0) {
                $s .= "<li style='padding-top: 10px;'>" . (isset($fe['file']) ? $fe['file'] : "") . " : " . (isset($fe['line']) ? $fe['line'] : "") . "</li>";
            } elseif (isset($fe['class']) && strpos(" " . $fe['class'], "ErrorHandler") > 0) {
                $s .= "<li style='padding-top: 10px;'>" . (isset($fe['file']) ? $fe['file'] : "") . " : " . (isset($fe['line']) ? $fe['line'] : "") . "</li>";
            } else {
                
                $s .= "<li style='padding-top: 10px;'><b>" . (isset($fe['file']) ? $fe['file'] : "") . " : " . (isset($fe['line']) ? $fe['line'] : "") . "</b></li>";
                $mail .= "\t\t" . (isset($fe['file']) ? $fe['file'] : "") . "\r\n";
                $s .= "		<ul>";
                $s .= "			<li>" . (isset($fe['class']) ? $fe['class'] : "php");
                $s .= "				->" . (isset($fe['function']) ? $fe['function'] : "") . "</li>";
                
                $mail .= "\t\t\tKlasse: " . (isset($fe['class']) ? $fe['class'] : "") . "\r\n";
                $mail .= "\t\t\tFunktion: " . (isset($fe['function']) ? $fe['function'] : "") . "\r\n";
                $mail .= "\t\t\tZeile: " . (isset($fe['line']) ? $fe['line'] : "") . "\r\n";
                
                if (isset($fe['args']) && is_array($fe['args'])) {
                    $s .= "			<li>Parameter:</li>";
                    $s .= "				<ol>";
                    $mail .= "\t\t\tParameter:\r\n";
                    foreach ($fe['args'] as $p) {
                        if (is_string($p)) {
                            $s .= "<li>-->" . $p . "</li>";
                            $mail .= "\t\t\t\t-->: " . $p . "\r\n";
                        }
                    }
                    $s .= "				</ol>";
                } else {
                    $s .= "			<li>Parameter: <b>Keine</b></li>";
                }
                $s .= "		</ul>";
            }
        }
        $s .= "		</ol>";
        
        $s .= "<h3>User:</h3>";
        $s .= "		<pre>";
        if ($webUser != null && $webUser != "") {
            $s .= $webUser->getBenutzername();
            $mail .= "Benutzer: " . $webUser->getBenutzername() . "\r\n";
        } else {
            $s .= "Benutzer: unbekannt\r\n";
            $mail .= "Benutzer: unbekannt\r\n";
        }
        
        $s .= "		</pre>";
        $s .= "	</div>";
        $s .= "</div>";
        echo $s;
        
        if (SUPPORT_MAIL_ENABLED && $this->isOnline()) {
            $x = "<div class=\"mailstatus\">SupportMail an den Systemadministrator: ";
            if ($this->sendMail($mail)) {
                $x .= "GESENDET";
            } else {
                $x .= "NICHT GESENDET";
            }
            $x .= "</div>";
            echo $x;
        }
    }

    private function sendMail($content)
    {
        $retVal = false;
        if ($this->isOnline()) {
            $retVal = mail(SUPPORT_MAIL_ADDR, INSTALLATION_NAME . ": " . $this->text . " " . $this->zeile, $content, "from:" . SUPPORT_MAIL_FROM);
        }
        return $retVal;
    }

    private function isOnline()
    {
        return $_SERVER['REMOTE_ADDR'] != "127.0.0.1";
    }
}
?>