<?php

class Date
{

    private static $monatsName = array(
        1 => "Januar",
        2 => "Februar",
        3 => "März",
        4 => "April",
        5 => "Mai",
        6 => "Juni",
        7 => "Juli",
        8 => "August",
        9 => "September",
        10 => "Oktober",
        11 => "November",
        12 => "Dezember"
    );

    /**
     * 0 = Montag, 1 = Dienstag ...
     * 6 = Sonntag
     *
     * @param unknown_type $timestamp            
     */
    public static function getTagDerWoche($timestamp = "now"): int|float
    {
        if ($timestamp == "now")
            $timestamp = time();
        
        $t = date("w", $timestamp);
        return $t == 0 ? 6 : ($t - 1);
    }

    /**
     * 
     * @param int|float $iMonat 
     * @return string Name des Monats
     */
    public static function getMonatsnamen($iMonat): string
    {
        return Date::$monatsName[$iMonat];
    }

    public function getTime(): string
    {
        return date("H:i");
    }

    public static function getDate($timestamp = null): string
    {
        if ($timestamp == null)
            $timestamp = time();
        return date("d.m.Y", $timestamp);
    }

    public static function getSQLDate($timestamp = null): string
    {
        if ($timestamp == null)
            $timestamp = time();
        return date("Y-m-d", $timestamp);
    }

    /**
     * 
     * @param int $timestamp 
     * @return string Kalenderwoche
     */
    public function getKW($timestamp): string
    {
        return date("W", $timestamp);
    }

    /**
     * 
     * @param int $timestamp 
     * @return string Jahr
     */
    public function getYear($timestamp): string
    {
        return date("Y", $timestamp);
    }

    public function getMonthDate(): string
    {
        return date("d") . ". " . Date::$monatsName[date("n")] . " " . date("Y");
    }

    /**
     * 
     * @param int $timestampEinesTage 
     * @param string $format 
     * @return string[] String Array mit den 7 Tagen der Arbeitswoche. Montag = 0, Sonntag = 6
     */
    public static function berechneArbeitswoche($timestampEinesTage, $format = "d.m.Y")
    {
        $c = Date::berechneArbeitswocheTimestamp($timestampEinesTage);
        $retVal = array();
        for ($i = 0; $i < 7; $i ++) {
            $retVal[$i] = date($format, $c[$i]);
        }
        return $retVal;
    }

    /**
     * Gibt die Timestamps eines jeden Wochentags des angegeben Timestamps zurück
     *
     * Intern rechnet PHP mit Sonntags als ersten Tag der Woche. Das wird hier aber getrickst
     *
     * @param int $timestampEinesTage            
     * @return array
     */
    public static function berechneArbeitswocheTimestamp($timestampEinesTage)
    {
        // Das + 1 sorgt dafür, dass die Woche bei Montag anfängt
        $iTagDerWoche = - date("w", $timestampEinesTage) + 1;
        
        $arWochentageTS = array();
        for ($i = 0; $i < 7; $i ++) {
            // PHP8 Fix. Hier stand sonst strtotime(+- 4 days)
            if($iTagDerWoche < 0) {
                $arWochentageTS[$i] = strtotime($iTagDerWoche . " days", $timestampEinesTage);
            } else {
                $arWochentageTS[$i] = strtotime("+" . $iTagDerWoche . " days", $timestampEinesTage);
            }
            $iTagDerWoche ++;
        }
        
        return $arWochentageTS;
    }

    /**
     * 
     * @param string $datum 
     * @return bool true wenn das Datum ein Feiertag ist.
     */
    public static function isFeiertag($datum)
    {
        return "" != Date::berechneFeiertage($datum, BUNDESLAND);
    }

    /**
     * 
     * @param string $datum 
     * @return false|string 
     */
    public static function getFeiertagsBezeichnung($datum)
    {
        return Date::berechneFeiertage($datum, BUNDESLAND);
    }

    /**
     * 
     * @param string $datum 
     * @param string $bundesland 
     * @return false|string 
     */
    public static function berechneFeiertage($datum, $bundesland = 'nrw')
    {
        if (false == is_numeric($datum)) {
            $datum = strtotime($datum);
        }
        $datum = date("Y-m-d", $datum);
        
        $bundesland = strtoupper($bundesland);
        $datum = explode("-", $datum);
        
        $datum[1] = str_pad($datum[1], 2, "0", STR_PAD_LEFT);
        $datum[2] = str_pad($datum[2], 2, "0", STR_PAD_LEFT);
        
        if (! checkdate($datum[1], $datum[2], $datum[0]))
            return false;
        
        $datum_arr = getdate(mktime(0, 0, 0, $datum[1], $datum[2], $datum[0]));
        
        $easter_d = date("d", easter_date($datum[0]));
        $easter_m = date("m", easter_date($datum[0]));
        
        $status = 'Arbeitstag';
        if ($datum_arr['wday'] == 0 || $datum_arr['wday'] == 6)
            $status = 'Wochenende';
        
        if ($datum[1] . $datum[2] == '0101') {
            return 'Neujahr';
        } elseif ($datum[1] . $datum[2] == '0106' && ($bundesland == 'BW' || $bundesland == 'BY' || $bundesland == 'ST')) {
            return 'Heilige Drei Könige';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d - 2, $datum[0]))) {
            return 'Karfreitag';
        } elseif ($datum[1] . $datum[2] == $easter_m . $easter_d) {
            return 'Ostersonntag';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 1, $datum[0]))) {
            return 'Ostermontag';
        } elseif ($datum[1] . $datum[2] == '0501') {
            return 'Erster Mai';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 39, $datum[0]))) {
            return 'Christi Himmelfahrt';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 49, $datum[0]))) {
            return 'Pfingstsonntag';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 50, $datum[0]))) {
            return 'Pfingstmontag';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 60, $datum[0])) && ($bundesland == 'BW' || $bundesland == 'BY' || $bundesland == 'HE' || $bundesland == 'NW' || $bundesland == 'RP' || $bundesland == 'SL' || $bundesland == 'SN' || $bundesland == 'TH')) {
            return 'Fronleichnam';
        } elseif ($datum[1] . $datum[2] == '0815' && ($bundesland == 'SL' || $bundesland == 'BY')) {
            return 'Mariä Himmelfahrt';
        } elseif ($datum[1] . $datum[2] == '1003') {
            return 'Tag der deutschen Einheit';
        } elseif ($datum[1] . $datum[2] == '1031' && ($bundesland == 'BB' || $bundesland == 'MV' || $bundesland == 'SN' || $bundesland == 'ST' || $bundesland == 'TH')) {
            return 'Reformationstag';
        } elseif ($datum[1] . $datum[2] == '1101' && ($bundesland == 'BW' || $bundesland == 'BY' || $bundesland == 'NW' || $bundesland == 'RP' || $bundesland == 'SL')) {
            return 'Allerheiligen';
        } elseif ($datum[1] . $datum[2] == strtotime("-11 days", strtotime("1 sunday", mktime(0, 0, 0, 11, 26, $datum[0]))) && $bundesland == 'SN') {
            return 'Buß- und Bettag';
        } elseif ($datum[1] . $datum[2] == '1224') {
            return 'Heiliger Abend';
        } elseif ($datum[1] . $datum[2] == '1225') {
            return '1. Weihnachtsfeiertag';
        } elseif ($datum[1] . $datum[2] == '1226') {
            return '2. Weihnachtsfeiertag';
        } elseif ($datum[1] . $datum[2] == '1231') {
            return 'Silvester';
        } else {
            return "";
        }
    }

    public static function testeFeiertage()
    {
        for ($monat = 1; $monat <= 12; $monat ++) {
            echo '<strong>' . $monat . '</strong><br>';
            for ($tag = 1; $tag <= 31; $tag ++) {
                $tmp = Date::berechneFeiertage('2026-' . $monat . '-' . $tag, 'noe');
                if ($tmp == 'Arbeitstag' || $tmp == 'Wochenende' || $tmp == "") {
                    // echo $tag.'.'.$monat.': '.$tmp.'<br>';
                } else {
                    echo $tag . '.' . $monat . ': <strong>' . $tmp . '</strong><br>';
                }
            }
            echo '<br><br>';
        }
    }
}
?>