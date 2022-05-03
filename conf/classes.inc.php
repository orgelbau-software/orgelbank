<?php
include_once ROOTDIR . 'src/core/exceptions/class.BaseException.php';
include_once ROOTDIR . 'src/core/exceptions/class.IllegalArgumentException.php';
include_once ROOTDIR . 'src/core/exceptions/class.MethodUndefinedException.php';
include_once ROOTDIR . 'src/core/mail/class.SupportMail.php';
include_once ROOTDIR . 'src/core/security/class.IntrusionNotificationMailer.php';

// Composer Stuff
if(file_exists(ROOTDIR . 'vendor/autoload.php')) {
    require_once ROOTDIR . 'vendor/autoload.php';
} else {
    die("Bitte erst composer ausfuehren um benoetigte Pakete zu installieren.");
}

include_once ROOTDIR . 'src/core/excel/OrgelbankPHPSpreadsheetWriter.php';

include_once ROOTDIR . 'src/core/log/class.Trace.php';
include_once ROOTDIR . 'src/core/log/interface.Logger.php';
include_once ROOTDIR . 'src/core/log/class.EchoLogger.php';
include_once ROOTDIR . 'src/core/log/class.FirePHPLogger.php';
include_once ROOTDIR . 'src/core/log/class.DoNothingLogger.php';
include_once ROOTDIR . 'src/core/log/class.Log.php';
include_once ROOTDIR . 'src/core/util/class.Utilities.php';
include_once ROOTDIR . 'src/core/util/class.WaehrungUtil.php';

include_once ROOTDIR . 'src/core/session/class.OrgelbankSessionHandler.php';

include_once ROOTDIR . 'src/core/db/interface.DBProvider.php';
include_once ROOTDIR . 'src/core/db/class.MySQLDBProvider.php';
include_once ROOTDIR . 'src/core/db/class.MySQLiDBProvider.php';
include_once ROOTDIR . 'src/core/db/class.DB.php';
include_once ROOTDIR . 'src/core/tpl/class.Output.php';
include_once ROOTDIR . 'src/core/tpl/class.RTFOutput.php';
include_once ROOTDIR . 'src/core/tpl/class.ODTOutput.php';
include_once ROOTDIR . 'src/core/tpl/class.MSWordOutput.php';
include_once ROOTDIR . 'src/core/tpl/class.Template.php';
include_once ROOTDIR . 'src/core/tpl/class.TemplateRepeater.php';
include_once ROOTDIR . 'src/core/tpl/class.BufferedTemplate.php';
include_once ROOTDIR . 'src/core/tpl/class.OutputRepeater.php';
include_once ROOTDIR . 'src/core/util/class.ArrayList.php';
include_once ROOTDIR . 'src/core/util/class.Hashtable.php';
include_once ROOTDIR . 'src/core/util/class.Date.php';
include_once ROOTDIR . 'src/core/tpl/class.JSONTemplate.php';

include_once ROOTDIR . 'src/pagecontrol/interface.PostRequestHandler.php';
include_once ROOTDIR . 'src/pagecontrol/interface.PostRequestValidator.php';
include_once ROOTDIR . 'src/pagecontrol/interface.GetRequestHandler.php';
include_once ROOTDIR . 'src/pagecontrol/class.RequestHandler.php';

include_once ROOTDIR . 'src/pagecontrol/class.PageApp.php';
include_once ROOTDIR . 'src/pagecontrol/class.PageController.php';
include_once ROOTDIR . 'src/pagecontrol/class.SubPage.php';

include_once ROOTDIR . 'src/config/class.Constant.php';
include_once ROOTDIR . 'src/core/constants/class.ConstantLoader.php';
include_once ROOTDIR . 'src/core/constants/class.ConstantSetter.php';

include_once ROOTDIR . 'src/core/db/class.DatabaseStorageObjekt.php';
include_once ROOTDIR . 'src/core/db/class.SimpleDatabaseStorageObjekt.php';
include_once ROOTDIR . 'src/core/db/class.AdvancedDatabaseStorageObjekt.php';
include_once ROOTDIR . 'src/core/db/class.DatabaseStorageObjektCollection.php';
include_once ROOTDIR . 'src/entities/class.Gemeinde.php';
include_once ROOTDIR . 'src/gemeinde/class.GemeindeUtilities.php';
include_once ROOTDIR . 'src/gemeinde/class.GemeindeRequestHandler.php';
include_once ROOTDIR . 'src/gemeinde/controller.GemeindeListeAction.php';
include_once ROOTDIR . 'src/entities/class.Adresse.php';
include_once ROOTDIR . 'src/entities/class.Ansprechpartner.php';
include_once ROOTDIR . 'src/ansprechpartner/class.AnsprechpartnerUtilities.php';
include_once ROOTDIR . 'src/beans/interface.Bean.php';
include_once ROOTDIR . 'src/beans/class.GemeindeAnsprechpartner.php';
include_once ROOTDIR . 'src/beans/class.KircheOrtBean.php';
include_once ROOTDIR . 'src/beans/class.StempeluhrViewBean.php';
include_once ROOTDIR . 'src/entities/class.Orgel.php';
include_once ROOTDIR . 'src/orgel/class.OrgelRequestHandler.php';
include_once ROOTDIR . 'src/orgel/class.OrgelOffeneWartungenRequestHandler.php';
include_once ROOTDIR . 'src/orgel/class.OrgelUtilities.php';
include_once ROOTDIR . 'src/orgel/controller.OrgelDetailsAction.php';
include_once ROOTDIR . 'src/orgel/controller.WartungsListeAction.php';
include_once ROOTDIR . 'src/orgel/controller.WartungsprotokolleAction.php';
include_once ROOTDIR . 'src/beans/class.AdressBean.php';
include_once ROOTDIR . 'src/beans/class.DirectionsBean.php';
include_once ROOTDIR . 'src/beans/class.GemeindeKarteBean.php';
include_once ROOTDIR . 'src/beans/class.AnsprechpartnerBean.php';
include_once ROOTDIR . 'src/beans/class.OrgelGemeinde.php';
include_once ROOTDIR . 'src/beans/class.OrgelListeBean.php';
include_once ROOTDIR . 'src/beans/class.OrgelListeExportBean.php';
include_once ROOTDIR . 'src/beans/class.GemeindeListeBean.php';
include_once ROOTDIR . 'src/beans/class.GemeindeListeExportBean.php';
include_once ROOTDIR . 'src/beans/class.RegisterGroessenBean.php';
include_once ROOTDIR . 'src/beans/class.ManualBean.php';
include_once ROOTDIR . 'src/entities/class.Wartung.php';
include_once ROOTDIR . 'src/entities/class.Wartungsprotokoll.php';
include_once ROOTDIR . 'src/orgel/class.WartungUtilities.php';
include_once ROOTDIR . 'src/orgel/class.WartungsprotokollUtilities.php';
include_once ROOTDIR . 'src/entities/class.Register.php';
include_once ROOTDIR . 'src/orgel/class.RegisterUtilities.php';
include_once ROOTDIR . 'src/entities/class.OptionValueObjekt.php';
include_once ROOTDIR . 'src/core/options/class.OptionValuesUtilities.php';
include_once ROOTDIR . 'src/entities/class.Projekt.php';
include_once ROOTDIR . 'src/entities/class.Stempeluhr.php';
include_once ROOTDIR . 'src/projekt/class.StempeluhrUtilities.php';
include_once ROOTDIR . 'src/projekt/class.ProjektUtilities.php';
include_once ROOTDIR . 'src/projekt/class.ProjektRequestHandler.php';
include_once ROOTDIR . 'src/projekt/class.ProjektListeRequestHandler.php';
include_once ROOTDIR . 'src/projekt/class.ProjektStempeluhrAction.php';
include_once ROOTDIR . 'src/projekt/controller.ProjektStundenFreigabeAction.php';
include_once ROOTDIR . 'src/projekt/controller.ArbeitsTagUndWocheStatusWechselAction.php';
include_once ROOTDIR . 'src/projekt/controller.ArbeitszeitVerwaltungAction.php';
include_once ROOTDIR . 'src/entities/class.Urlaub.php';
include_once ROOTDIR . 'src/projekt/class.UrlaubsUtilities.php';
include_once ROOTDIR . 'src/projekt/controller.UrlaubsVerwaltungAction.php';
include_once ROOTDIR . 'src/projekt/controller.JahresurlaubAnlegenAction.php';
include_once ROOTDIR . 'src/projekt/controller.ProjektDetailsAction.php';
include_once ROOTDIR . 'src/projekt/class.ProjektKostenRechner.php';
include_once ROOTDIR . 'src/entities/class.Aufgabe.php';
include_once ROOTDIR . 'src/projekt/class.AufgabeUtilities.php';
include_once ROOTDIR . 'src/entities/class.ProjektAufgabe.php';
include_once ROOTDIR . 'src/projekt/class.ProjektAufgabeUtilities.php';
include_once ROOTDIR . 'src/entities/class.ProjektRechnung.php';
include_once ROOTDIR . 'src/entities/class.NebenkostenRechnung.php';
include_once ROOTDIR . 'src/projekt/class.ProjektRechnungUtilities.php';
include_once ROOTDIR . 'src/projekt/class.NebenkostenRechnungUtilities.php';
include_once ROOTDIR . 'src/entities/class.Reisekosten.php';
include_once ROOTDIR . 'src/projekt/class.ReisekostenUtilities.php';
include_once ROOTDIR . 'src/benutzer/class.PasswordUtility.php';
include_once ROOTDIR . 'src/entities/class.Benutzer.php';
include_once ROOTDIR . 'src/benutzer/class.BenutzerUtilities.php';
include_once ROOTDIR . 'src/benutzer/class.WebBenutzer.php';
include_once ROOTDIR . 'src/beans/class.AufgabeMitarbeiter.php';
include_once ROOTDIR . 'src/entities/class.Konfession.php';
include_once ROOTDIR . 'src/gemeinde/class.KonfessionUtilities.php';
include_once ROOTDIR . 'src/entities/class.Arbeitstag.php';
include_once ROOTDIR . 'src/projekt/class.ArbeitstagUtilities.php';
include_once ROOTDIR . 'src/entities/class.Arbeitswoche.php';
include_once ROOTDIR . 'src/projekt/class.ArbeitswocheUtilities.php';
include_once ROOTDIR . 'src/beans/class.ZeiterfassungDTO.php';
include_once ROOTDIR . 'src/beans/class.ProjektRechnungsListeBean.php';
include_once ROOTDIR . 'src/projekt/class.ZeiterfassungUtilities.php';
include_once ROOTDIR . 'src/projekt/class.ProjektMaterialRechnungenAction.php';

include_once ROOTDIR . 'src/projekt/class.BasisPDF.php';
include_once ROOTDIR . 'src/projekt/class.StundenzettelPDF.php';
include_once ROOTDIR . 'src/projekt/class.MitarbeiterStundenzettelAction.php';

include_once ROOTDIR . 'src/entities/class.RechnungsPosition.php';
include_once ROOTDIR . 'src/entities/class.Rechnung.php';
include_once ROOTDIR . 'src/entities/class.PositionsRechnung.php';
include_once ROOTDIR . 'src/rechnung/class.RechnungsPositionUtilities.php';
include_once ROOTDIR . 'src/rechnung/class.RechnungUtilities.php';
include_once ROOTDIR . 'src/rechnung/class.StundenRechnungUtilities.php';
include_once ROOTDIR . 'src/entities/class.PflegeRechnung.php';
include_once ROOTDIR . 'src/rechnung/class.PflegeRechnungUtilities.php';
include_once ROOTDIR . 'src/entities/class.StundenRechnung.php';
include_once ROOTDIR . 'src/entities/class.AbschlagsRechnung.php';
include_once ROOTDIR . 'src/rechnung/class.AbschlagRechnungUtilities.php';
include_once ROOTDIR . 'src/entities/class.EndRechnung.php';
include_once ROOTDIR . 'src/beans/class.RechnungView.php';
include_once ROOTDIR . 'src/rechnung/class.RechnungViewUtilities.php';
include_once ROOTDIR . 'src/rechnung/class.RechnungsPositionsSuggestionAction.php';
include_once ROOTDIR . 'src/rechnung/class.RechnungsListeRequestHandler.php';

include_once ROOTDIR . 'src/rechnung/class.RechnungOutput.php';
include_once ROOTDIR . 'src/rechnung/class.RechnungTemplateBuilder.php';
include_once ROOTDIR . 'src/rechnung/class.PositionsRechnungsOutput.php';
include_once ROOTDIR . 'src/rechnung/class.PflegeRechnungOutput.php';
include_once ROOTDIR . 'src/rechnung/class.PflegeRechnungTemplateBuilder.php';
include_once ROOTDIR . 'src/rechnung/class.StundenRechnungOutput.php';
include_once ROOTDIR . 'src/rechnung/class.StundenRechnungTemplateBuilder.php';
include_once ROOTDIR . 'src/rechnung/class.AbschlagRechnungOutput.php';
include_once ROOTDIR . 'src/rechnung/class.AbschlagRechnungTemplateBuilder.php';
include_once ROOTDIR . 'src/rechnung/class.EndRechnungOutput.php';
include_once ROOTDIR . 'src/rechnung/class.EndRechnungTemplateBuilder.php';

include_once ROOTDIR . 'src/core/web/interface.HTMLSelectDescriptor.php';
include_once ROOTDIR . 'src/core/web/class.HTMLSelect.php';
include_once ROOTDIR . 'src/core/web/class.HTMLSelectForArray.php';
include_once ROOTDIR . 'src/core/web/class.HTMLSelectForKey.php';
include_once ROOTDIR . 'src/core/web/class.HTMLSelectForDSOC.php';
include_once ROOTDIR . 'src/core/web/class.HTMLSelectForDSOCWithMultiGetter.php';
include_once ROOTDIR . 'src/core/web/class.HTMLDatalistForArray.php';
include_once ROOTDIR . 'src/core/web/class.HTMLDatalist.php';
include_once ROOTDIR . 'src/core/web/class.HTMLRedirect.php';
include_once ROOTDIR . 'src/core/web/class.HTMLStatus.php';
include_once ROOTDIR . 'src/core/web/class.HTMLFehlerseite.php';
include_once ROOTDIR . 'src/core/web/class.HTMLSicherheitsAbfrage.php';
include_once ROOTDIR . 'src/core/web/class.Quickjump.php';

include_once ROOTDIR . 'src/pagecontrol/class.SeitenStatistik.php';
include_once ROOTDIR . 'src/entities/class.BenutzerVerlauf.php';
include_once ROOTDIR . 'src/benutzer/class.BenutzerVerlaufTracker.php';


include_once ROOTDIR . 'src/ansprechpartner/controller.Ansprechpartner.php';
include_once ROOTDIR . 'src/orgel/controller.OrgelBildAction.php';
include_once ROOTDIR . 'src/orgel/controller.OrgelWartungAction.php';
include_once ROOTDIR . 'src/orgel/controller.Orgel.php';
include_once ROOTDIR . 'src/orgel/controller.OrgelListeAction.php';
include_once ROOTDIR . 'src/gemeinde/controller.Gemeinde.php';
include_once ROOTDIR . 'src/orgel/controller.Disposition.php';
include_once ROOTDIR . 'src/rechnung/controller.Rechnung.php';
include_once ROOTDIR . 'src/projekt/controller.Projekt.php';
include_once ROOTDIR . 'src/projekt/controller.ProjektBearbeitenAction.php';
include_once ROOTDIR . 'src/controller/controller.Einstellung.php';
include_once ROOTDIR . 'src/benutzer/controller.Benutzer.php';

include_once ROOTDIR . 'src/disposition/class.DispositionsUtilities.php';
include_once ROOTDIR . 'src/disposition/controller.DispositionBearbeitenAction.php';

include_once ROOTDIR . 'src/gemeinde/controller.GemeindeDruckansicht.php';
include_once ROOTDIR . 'src/gemeinde/controller.GemeindeLoeschenAction.php';
include_once ROOTDIR . 'src/gemeinde/controller.GemeindeGeocodeAction.php';
include_once ROOTDIR . 'src/gemeinde/controller.GemeindeGeocodeAPIAction.php';
include_once ROOTDIR . 'src/gemeinde/controller.GemeindeKarteAction.php';
include_once ROOTDIR . 'src/gemeinde/controller.GemeindeGoogleMapsForwardAction.php';

include_once ROOTDIR . 'src/einstellung/controller.OptionValuesAction.php';

include_once ROOTDIR . 'src/einstellung/controller.BenutzerVerlaufUebersichtAction.php';
include_once ROOTDIR . 'src/einstellung/class.BenutzerVerlaufUtilities.php';
include_once ROOTDIR . 'src/beans/class.BenutzerVerlaufUebersichtBean.php';

include_once ROOTDIR . 'src/services/geolocation/GoogleMapsGeocoder.php';
include_once ROOTDIR . 'src/services/geolocation/interface.IGeolocationConstants.php';
include_once ROOTDIR . 'src/services/geolocation/interface.IDirectionsService.php';
include_once ROOTDIR . 'src/services/geolocation/interface.IGeocoderService.php';
include_once ROOTDIR . 'src/services/geolocation/class.GoogleMapsWebService.php';
include_once ROOTDIR . 'src/services/geolocation/class.GoogleMapsGeocoderService.php';
include_once ROOTDIR . 'src/services/geolocation/class.GoogleMapsDirectionsService.php';
include_once ROOTDIR . 'src/services/geolocation/class.OrgelbankGoogleMapsGeocoder.php';
include_once ROOTDIR . 'src/services/geolocation/class.MockGeocoderService.php';
include_once ROOTDIR . 'src/services/geolocation/class.MockDirectionService.php';
include_once ROOTDIR . 'src/services/geolocation/class.OrgelbankGoogleMapsDirectionsService.php';

?>