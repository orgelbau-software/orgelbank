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
    public static function getTagDerWoche($timestamp = "now")
    {
        if ($timestamp == "now")
            $timestamp = time();
        
        $t = date("w", $timestamp);
        return $t == 0 ? 6 : ($t - 1);
    }

    public static function getMonatsnamen($iMonat)
    {
        return Date::$monatsName[$iMonat];
    }

    public function getTime()
    {
        return date("H:i");
    }

    public static function getDate($timestamp = null)
    {
        if ($timestamp == null)
            $timestamp = time();
        return date("d.m.Y", $timestamp);
    }

    public static function getSQLDate($timestamp = null)
    {
        if ($timestamp == null)
            $timestamp = time();
        return date("Y-m-d", $timestamp);
    }

    public function getKW($timestamp)
    {
        return date("W", $timestamp);
    }

    public function getYear($timestamp)
    {
        return date("Y", $timestamp);
    }

    public function getMonthDate()
    {
        return date("d") . ". " . Date::$monatsName[date("n")] . " " . date("Y");
    }

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
            $arWochentageTS[$i] = strtotime("+" . $iTagDerWoche . " days", $timestampEinesTage);
            $iTagDerWoche ++;
        }
        
        return $arWochentageTS;
    }

    public function isFeiertag($datum)
    {
        return "" != Date::berechneFeiertage($datum);
    }

    public static function getFeiertagsBezeichnung($datum)
    {
        return Date::berechneFeiertage($datum);
    }

    public static function berechneFeiertage($datum, $bundesland = 'nrw')
    {
        if (false == is_numeric($datum)) {
            $datum = strtotime($datum);
        }
        $datum = date("Y-m-d", $datum);
        $datum = explode("-", $datum);
        
        if (! checkdate($datum[1], $datum[2], $datum[0]))
            return false;
        
        // $datum_arr = getdate(mktime(0, 0, 0, $datum[1], $datum[2], $datum[0]));
        
        $easter_d = date("d", easter_date($datum[0]));
        $easter_m = date("m", easter_date($datum[0]));
        
        // $status = 'Arbeitstag';
        // if($datum_arr['wday'] == 0 || $datum_arr['wday'] == 6)
        // $status = 'Wochenende';
        
        if ($datum[1] . $datum[2] == '0101') {
            return 'Neujahr';
        } elseif ($datum[1] . $datum[2] == '0106') {
            return 'Heilige Drei Könige';
        } elseif ($datum[1] . $datum[2] == '0319' && ($bundesland == 'k' || $bundesland == 'st' || $bundesland == 't' || $bundesland == 'v')) {
            return 'Josef';
        } elseif ($datum[1] . $datum[2] == $easter_m . $easter_d) {
            return 'Ostersonntag';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 1, $datum[0]))) {
            return 'Ostermontag';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d - 2, $datum[0]))) {
            return 'Karfreitag';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 39, $datum[0]))) {
            return 'Christi Himmelfahrt';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 49, $datum[0]))) {
            return 'Pfingstsonntag';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 50, $datum[0]))) {
            return 'Pfingstmontag';
        } elseif ($datum[1] . $datum[2] == date("md", mktime(0, 0, 0, $easter_m, $easter_d + 60, $datum[0]))) {
            return 'Fronleichnam';
        } elseif ($datum[1] . $datum[2] == '0501') {
            return 'Erster Mai';
        } elseif ($datum[1] . $datum[2] == '0504' && $bundesland == 'ooe') {
            return 'Florian';
        } elseif ($datum[1] . $datum[2] == '0815' && $bundesland == 'b') {
            return 'Mariä Himmelfahrt';
        } elseif ($datum[1] . $datum[2] == '0924' && $bundesland == 's') {
            return 'Rupertitag';
        } elseif ($datum[1] . $datum[2] == '1010' && $bundesland == 'k') {
            return 'Tag der Volksabstimmung';
        } elseif ($datum[1] . $datum[2] == '1026') {
            return 'Nationalfeiertag';
		} elseif ($datum[1] . $datum[2] == '1003') {
            return 'Tag der Deutschen Einheit';
        } elseif ($datum[1] . $datum[2] == '1101') {
            return 'Allerheiligen';
        } elseif ($datum[1] . $datum[2] == '1111' && $bundesland == 'b') {
            return 'Martini';
        } elseif ($datum[1] . $datum[2] == '1115' && ($bundesland == 'noe' || $bundesland == 'w')) {
            return 'Leopoldi';
        } elseif ($datum[1] . $datum[2] == '1208') {
            return 'Mariä Empfängnis';
        } elseif ($datum[1] . $datum[2] == '1224') {
            return 'Heiliger Abend';
        } elseif ($datum[1] . $datum[2] == '1225') {
            return '1. Weihnachtstag';
        } elseif ($datum[1] . $datum[2] == '1226') {
            return '2. Weihnachtstag';
        } else {
            return "";
        }
    }

    public static function testeFeiertage()
    {
        for ($monat = 1; $monat <= 12; $monat ++) {
            echo '<strong>' . $monat . '</strong><br>';
            for ($tag = 1; $tag <= 31; $tag ++) {
                $tmp = Date::berechneFeiertage('2008-' . $monat . '-' . $tag, 'noe');
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