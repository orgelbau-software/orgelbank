<?php

/**
 * @author swatermeyer
 * @version $Revision: $
 *
 */
class OptionValueUtilities
{

    public static function getEditableOptions($strOrderBy = "")
    {
        $sql = "SELECT
					*
				FROM 
					option_meta
				WHERE
					option_editable = 1 ";
        if ($strOrderBy != "") {
            $sql .= $strOrderBy;
        }
        return OptionValueUtilities::queryDB($sql);
    }

    public static function getAutoloadOptions()
    {
        $sql = "SELECT
					*
				FROM 
					option_meta
				WHERE
					option_autoload = 1";
        return OptionValueUtilities::queryDB($sql);
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new OptionvalueObjekt();
                $tmp->doLoadFromArray($objekt);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}
?>