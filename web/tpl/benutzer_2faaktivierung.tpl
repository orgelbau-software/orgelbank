<h3>2 Faktor Authentifizierung</h3>

Scanne dieses Bild mit deiner 2-FA App.
<form method="post" action="index.php?page=8&do=146">
<img src="<!--QRCodeImageURL-->" />

<h3>OTP Pin Eingabe</h3>
Gib den Einmal Code aus deiner App ein.

<input type="text"  id="otp" name="otp" minlength="6" maxlength="6" autocomplete="one-time-code" required />

<input type="submit" name="submit" value="Aktivieren"/>
<input type="hidden" name="secret" value="<!--Secret-->" />
<!--Statusmeldung-->

</form>