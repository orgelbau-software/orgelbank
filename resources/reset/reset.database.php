<?php
include "../../conf/config.inc.php";
if($_POST && isset($_POST['password']) && $_POST['password'] == MYSQL_PASS) {
  error_reporting(E_ALL);
  $db = DB::getInstance();
  $db->connect();
  
  
  $sql = array();
  $sql[] = "TRUNCATE TABLE adresse;";
  $sql[] = "TRUNCATE TABLE arbeitstag;";
  $sql[] = "TRUNCATE TABLE arbeitswoche;";
  $sql[] = "TRUNCATE TABLE ansprechpartner;";
  $sql[] = "TRUNCATE TABLE aufgabe_mitarbeiter;";
  $sql[] = "TRUNCATE TABLE benutzer;";
  $sql[] = "TRUNCATE TABLE benutzerverlauf;";
  $sql[] = "TRUNCATE TABLE disposition;";
  $sql[] = "TRUNCATE TABLE gemeinde;";
  $sql[] = "TRUNCATE TABLE orgel;";
  $sql[] = "TRUNCATE TABLE gemeindeansprechpartner;";
  $sql[] = "TRUNCATE TABLE projekt_aufgabe;";
  $sql[] = "TRUNCATE TABLE projekt_rechnung;";
  $sql[] = "TRUNCATE TABLE projekt;";
  $sql[] = "TRUNCATE TABLE rechnung_abschlag;";
  $sql[] = "TRUNCATE TABLE rechnung_pflege;";
  $sql[] = "TRUNCATE TABLE rechnung_end;";
  $sql[] = "TRUNCATE TABLE rechnung_position;";
  $sql[] = "TRUNCATE TABLE rechnung_stunde;";
  $sql[] = "TRUNCATE TABLE reisekosten;";
  $sql[] = "TRUNCATE TABLE seitenstatistik;";
  $sql[] = "TRUNCATE TABLE wartung;";
  
 
  
  
  
  $aw = ArbeitswocheUtilities::createArbeitswoche(time());
  $sql[] = "DELETE FROM arbeitswoche WHERE aw_kw = " . $aw->getKalenderWoche() . " AND aw_jahr = " . $aw->getJahr();
  $sql[] = "INSERT INTO 
			arbeitswoche
    		(
    		be_id,
    		aw_wochenstart,
    		aw_kw,
    		aw_jahr,
    		aw_stunden_ist,
    		aw_stunden_soll,
    		aw_stunden_dif,
    		aw_eingabe_komplett,
    		aw_eingabe_moeglich,
    		aw_eingabe_gebucht,
    		aw_lastchange,
    		aw_createdate
    		)
				SELECT
					b.be_id,
					'" . $aw->getWochenStart() . "',
					" . $aw->getKalenderWoche() . ",
					" . $aw->getJahr() . ",
					0,
					b.be_std_gesamt,
					b.be_std_gesamt * -1,
					0,
					1,
					0,
					NOW(),
					NOW()
				FROM 
					benutzer b
				WHERE
					b.be_zeiterfassung = 1 AND
					b.be_geloescht = 0 AND
					b.be_aktiviert = 1";
  
  
  foreach($sql as $key => $val) {
    echo $val . "<br>";
    $db->NonSelectQuery($val);
  }
  
  
  $admin = new Benutzer();
  $admin->setBenutzername("swatermeyer");
  $admin->setPasswort(md5(MYSQL_PASS));
  $admin->setBenutzerlevel(10);
  $admin->setDemo(1);
  $admin->setAktiviert(1);
  $admin->speichern();
  
   ConstantSetter::updateOption("orgelbank_api_key", ORGELBANK_API_KEY);
   ConstantSetter::updateOption("site_title", INSTALLATION_NAME);
  
  $db->disconnect();
  echo "<h2>Datenbank zurueckgesetzt</h2>";
} else {
  ?>
<form action="reset.database.php" method="post">
	<h1>Datenbank zuruecksetzen</h1>
	<input type="password" name="password" value="" /> <input type="submit"
		name="submit" value="Datenbank zuruecksetzen" />
</form>
<?php
}
?>