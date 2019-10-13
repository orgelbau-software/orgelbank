<?php

class BenutzerVerlaufUtilities
{

    /**
     *
     * @return ArrayList
     */
    public static function loadBenutzerVerlaufUebersicht()
    {
        $sql = "SELECT
			     v.*
		    FROM
			 zzz_views v
            ORDER BY v.bv_max DESC";
        return BenutzerVerlaufUtilities::query($sql);
    }

    private static function query($sql)
    {
        $oCol = new ArrayList();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new BenutzerVerlaufUebersichtBean();
                $tmp->init($rs);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}