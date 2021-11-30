<?php

class ProjektKostenRechner
{

    public function calculate($angebotspreis, $ha, $projektRechnungenProAufgabe, $projektLohnkostenProHauptaufgabe, $reisekostenZusammenfassung, $dblNebenkostenSumme = 0)
    {
        $gesPlankosten = $gesRechnungen = $gesLohn = 0;
        if (count($projektRechnungenProAufgabe) > 0) {
            $merged = array_merge(array_keys($projektRechnungenProAufgabe), array_keys($projektLohnkostenProHauptaufgabe));
            $merged = array_combine($merged, $merged);
            foreach ($ha as $haufgabe) {
                if (isset($merged[$haufgabe->getID()])) {
                    unset($merged[$haufgabe->getID()]);
                }
            }
            if (count($merged) > 0) {
                foreach ($merged as $val) {
                    $tmp = new Aufgabe($val);
                    $tmp = ProjektAufgabeUtilities::copyAufgabeToProjektAufgabe($tmp);
                    $tmp->setSelected("false");
                    $ha->add($tmp);
                }
            }
        }
        
        // Aufgaben / Kosten Übersicht
        foreach ($ha as $haufgabe) {
            $tmpBezeichnung = $haufgabe->getBezeichnung();
            if ($haufgabe->getSelected() == "false")
                $tmpBezeichnung .= "*";
            
            $tmpRech = $tmpLohnKosten = 0;
            if (isset($projektRechnungenProAufgabe[$haufgabe->getID()])) {
                $gesRechnungen += $projektRechnungenProAufgabe[$haufgabe->getID()];
            }
            if (isset($projektLohnkostenProHauptaufgabe[$haufgabe->getID()])) {
                $gesLohn += $projektLohnkostenProHauptaufgabe[$haufgabe->getID()];
            }
            
            $gesPlankosten += $haufgabe->getPlankosten();
        }
        $gesamtkosten = $gesLohn + $gesRechnungen + $dblNebenkostenSumme + $reisekostenZusammenfassung['gesamt'];
        $differenzPlanGesamt = $gesPlankosten - $gesamtkosten;
        return array(
            "aufgaben" => $ha,
            "plankosten" => $gesPlankosten,
            "lohnkosten" => $gesLohn,
            "rechnungen" => $gesRechnungen,
            "reisekosten" => $reisekostenZusammenfassung['gesamt'],
            "nebenkosten" => $dblNebenkostenSumme,
            "gesamtkosten" => $gesamtkosten,
            "differenz_plan_gesamt" => $differenzPlanGesamt,
            "gewinn_oder_verlust" => ($angebotspreis - $gesamtkosten)
        );
    }
}