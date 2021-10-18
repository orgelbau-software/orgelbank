<script type="text/javascript">
var $loadedMWS = [<!--jsArrayInput-->];

$(function() {
  jQuery.each($loadedMWS, function() {
    $("#IMG" + this).attr("src", "web/images/icons/remove_minus_sign_small.png");
  });
});
</script>

<h3>Arbeitswochen &Uuml;bersicht</h3>
<hr/>
<a class="buttonlink iconButton dayButton" href="index.php?page=6&do=108&filter=aktuell" title="8 Wochen">Letzten 8 Wochen</a>
<a class="buttonlink iconButton monthButton" href="index.php?page=6&do=108&filter=alle" title="Alle">Alle</a>
<hr/>
<!--Statusmeldung-->
<form method="post" action="index.php?page=6&do=109">
<table class="liste">
  <tr>
    <th colspan="2">KW</th>
    <th>Datum</th>
    <th>Mitarbeiter</th>
    <th>Bisher eingetragen</th>
    <th>Status</th>
    <th><!-- Buchen--></th>
  </tr>
  <!--Datensaetze-->
</table>
</form>