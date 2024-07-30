<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auftragsbogen <!--Kirche--></title>

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 10pt;
        }
        
        th {
            text-align: left;
            font-size: 0.875em;
            vertical-align: top;
            }
        
        td {
            padding-right: 20px;
            font-size: 0.875em;
            vertical-align: top;
            }

        table.orgeldetails th {
            font-weight: normal;
            vertical-align: top;
            font-size: 0.875em;
        }

        table.orgeldetails td {
            font-weight: bold;
            vertical-align: top;
            font-size: 0.875em;
        }

        h2 {
            margin-bottom: 3px;
            font-size: 1.275em;
        }
    </style>
<head>
<body>
<table>
    <tr>
        <td><input type="checkbox" name="stimmauftrag" id="stimmauftrag"><label for="stimmauftrag">Stimmauftrag</label></td>
        <td><input type="checkbox" name="wartungsauftrag" id="wartungsauftrag"><label for="wartungsauftrag">Wartungsauftrag</label></td>
        <td><input type="checkbox" name="reparaturauftrag" id="reparaturauftrag"><label for="reparaturauftrag">Reparaturauftrag</label></td>
        <td><label for="mitarbeiter">MA:</label> <select name="mitarbeiter" type="select"><!--MitarbeiterListe--></select></td>
    </tr>
</table>

<table>
    <tr>
        <td>Datum: <input type="date" name="stimmauftrag" id="datum"></td>
        <td>Uhrzeit: <input type="time" name="uhrzeit" id="uhrzeit"> Uhr</td>
        <td>bis spätestens: <input type="time" name="uhrzeit_spaetestens" id="uhrzeit_spaetestens"> Uhr</td>
    </tr>
</table>

<table>
    <tr>
        <td>
            <input type="checkbox" name="vorher_anrufen" id="vorher_anrufen"><label for="vorher_anrufen">vorher anrufen:</label>
            <input type="text" name="vorher_anrufen_telefonnr" id="vorher_anrufen_telefonnr">
        </td>
        <td>
            <label for="verbinden_mit">verbinden mit Auftrag:</label>
            <input type="text" name="verbinden_mit" id="verbinden_mit">
        </td>
    </tr>
</table>

<h2><!--Kirche--></h2>
<table>
    <tr>
        <td style="font-weight: bold; padding-right: 20px;">
            Kirche</br>
            <!--Kirche-->
        </td>
        <td style=" padding-right: 20px;">
            Pfarramt</br>
            <!--RKirche-->
        </td>
        <td>Bezirk: <!--Bezirk--></td>
    </tr>
    <tr>
        <td>
            <!--Strasse--> <!--Hsnr-->
        </td>
        <td>
            <!--RStrasse--> <!--RHsnr-->
        </td>
        <td>Orgel: <!--OrgelID--></td>
    </tr>
    <tr>
        <td>
            <!--PLZ--> <!--Ort-->
        </td>
        <td>
            <!--RPLZ--> <!--ROrt-->
        </td>
        <td>&nbsp;</td>
    </tr>
</table>

<h2>Ansprechpartner</h2>
<table>
    <thead>
        <tr>
            <th>Funktion</th>
            <th>Name</th>
            <th>Telefon</th>
            <th>Mobil</th>
        </tr>
    </thead>
    <tbody>
        <!--AnsprechpartnerListe-->
    </tbody>
</table>

<h2>Orgel-Daten</h2>
<table class="orgeldetails">
    <tr>
        <th>Erbauer:</th>
        <td><!--Erbauer--></td>
        <th>Baujahr:</th>
        <td><!--Baujahr--></td>
        <th><!--RevisionArt-->:</th>
        <td><!--Revision--></td> 
    </tr>
    <tr>
        <th>Register:</th>
        <td><!--AnzahlManuale--></td>
        <th>Tonumfang HW:</th>
        <td><!--Tonumfang--></td>
        <th>Pedal:</th>
        <td><!--Pedal--></td> 
    </tr>
    <tr>
         <th>Windladen:</th>
        <td><!--Windladen--></td>
        <th>Spieltraktur:</th>
        <td><!--Spieltraktur--></td>
        <th>&nbsp;</th>
        <td>&nbsp;</td> 
    </tr>
    <tr>
        <th>Koppeln:</th>
        <td><!--Koppeln--></td> 
        <th>Registertraktur:</th>
        <td><!--Registertraktur--></td>
    </tr>
    <tr>
        <th>Pflegevertrag:</th>
        <td><!--Pflegevertrag--></td>
        <th>Zyklus:</th>
        <td><!--Zyklus--></td>
        <th>Stimmung nach:</th>
        <td><!--StimmungNach--></td> 
    </tr>
</table>

<table class="orgeldetails">
    <tr>
        <th>Winddrücke:</th>
        <th>I:</th>
        <td><!--WinddruckManual1--></td>
        <th>II:</th>
        <td><!--WinddruckManual2--></td>
        <th>III:</th>
        <td><!--WinddruckManual3--></td>
        <th>IV:</th>
        <td><!--WinddruckManual4--></td>
        <th>V:</th>
        <td><!--WinddruckManual5--></td>
        <th>Pedal:</th>
        <td><!--WinddruckPedal--></td>
    </tr>
</table>

<h2>Letzte Wartungen</h2>
<table>
    <!--WartungsListe-->
</table>

<h2>Vorzunehmende Arbeiten:</h2>
<table>
    <tr>
        <td><input type="checkbox" id="komplettstimmung" /> <label for="komplettstimmung">Komplettstimmung</label></td>
        <td><input type="checkbox" id="zungenstimmung" /> <label for="zungenstimmung">Zungenstimmung</label></td>
        <td><input type="checkbox" id="teilstimmung" /> <label for="teilstimmung">Teilstimmung</label></td>
    </tr>
    <tr>
        <td colspan="3">
            <input type="checkbox" id="folgenderegister" /> <label for="folgenderegister">folgende Register:</label>
            <input type="input" id="folgenderegistername" placeholder="..."/></label>
        </td>
    </tr>
    <tr>
        <td><input type="checkbox" id="geheizt_ja" /> <label for="geheizt_ja">es wird rechzeitig geheizt</label></td>
        <td><input type="checkbox" id="geheizt_nein" /> <label for="geheizt_nein">es wird NICHT geheizt</label></td>
        <td><input type="checkbox" id="tastenhalter" /> <label for="tastenhalter">Tastenhalter mitbringen</label></td>
    </tr>
    <tr>
        <td><input type="checkbox" id="einhausen" /> <label for="einhausen">Einhausen der Orgel</label></td>
        <td><input type="checkbox" id="aushausen" /> <label for="aushausen">Aushausen der Orgel</label></td>
        <td><input type="checkbox" id="granulat" /> <label for="granulat">Granulat auswechseln</label></td>
    </tr>
    <tr>
        <td><input type="checkbox" id="heuler" /> <label for="heuler">Heulerbeseitigung</label></td>
        <td><input type="checkbox" id="versager" /> <label for="versager">Versagerbeseitigung</label></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><input type="checkbox" id="einhausen" /> <label for="einhausen">Manualkoppel</label></td>
        <td><input type="checkbox" id="aushausen" /> <label for="aushausen">Pedalkoppel</label></td>
        <td>Werk: <input type="text" placeholder="..." value="<!--Werk-->" size="10" /> Ton: <input type="text" placeholder="..." value="<!--Ton-->" size="5"/></td>
    </tr>
    <tr>
        <td colspan="3">Allgemeine Anmerkungen: <!--AllgemeineAnmerkungen--></td>
    </tr>
    <tr>
        <td colspan="3">Notwendige Maßnahmen: <!--NotwendigeMassnahmen--></td>
    </tr>
    <tr>
        <td colspan="3">Bemerkung: <input type="text" placeholder="..." value="<!--Bemerkung-->" id="bemerkung" name="bemerkung" size="75"/></td>
    </tr>
</table>

<h2>Checkliste</h2>
<table>
    <tr>
        <td>Temperatur: __________ °C</td>
        <td>Luftfeuchte: __________ %</td>
        <td>Stimmon: __________  Hz</td>
    </tr>
    <tr>
        <td><input type="checkbox" id="schimmel" /> <label for="schimmel">Schimmelbefall</label></td>
        <td><input type="checkbox" id="schaedling" /> <label for="schaedling">Schädlingsbefall</label></td>
        <td>___________________________________</td>
    </tr>
</tabe>

<table>
    <tr>
        <td>Mitarbeiter: ____________________</td>
        <td>Fahrzeit: _____ Std.</td>
        <td>Arbeitszeit: _____ Std.</td>
        <td>km: _____</td>
    </tr>
</table>



</body>
</html>