<?php

class OrgelOffeneWartungenRequestHandler
{

    public function handleRequest()
    {
        $retVal = array();
        $retVal['zyklus'] = "0";
        $retVal['hideunknown'] = false;
        $retVal['SQLADD'] = "";
        
        if ($_POST) {
            if (isset($_POST['zyklus'])) {
                $retVal['zyklus'] = intval($_POST['zyklus']);
                if ($retVal['zyklus'] != 0) {
                    $retVal['SQLADD'] .= " AND o_zyklus = " . $retVal['zyklus'];
                }
            }
            if (isset($_POST['hideunknown'])) {
                $retVal['hideunknown'] = true;
                $retVal['SQLADD'] .= "";
                $retVal['SQLADD'] .= " AND o_letztepflege <> '' AND o_letztepflege <> '1970-01-01'";
            }
        }
        
        return $retVal;
    }
}