<?php

class GemeindeController
{

    public static function forwardGoogleMaps()
    {
        RequestHandler::handle(new GemeindeGoogleMapsForwardAction());
    }

    public static function zeigeGemeindeListeDruckansicht()
    {
        RequestHandler::handle(new GemeindeDruckansicht());
    }

    public static function ajaxGemeindeListeDruckansicht()
    {
        RequestHandler::handle(new GemeindeDruckansicht());
    }

    public static function loescheGemeinde()
    {
        RequestHandler::handle(new GemeindeLoeschenAction());
    }

    public static function geocodeGemeinde()
    {
        RequestHandler::handle(new GemeindeGeocodeAction());
    }

    public static function geocodeGemeindeAPI()
    {
        RequestHandler::handle(new GemeindeGeocodeAPIAction());
    }

    public static function zeigeGemeindeLandkarte()
    {
        RequestHandler::handle(new GemeindeKarteAction());
    }

    public static function neueGemeindeAnlegen()
    {
        RequestHandler::handle(new NeueGemeindeAnlegen());
    }

    public static function speichereGemeindeDetails()
    {
        RequestHandler::handle(new GemeindeDetailsAction());
    }

    public static function zeigeGemeindeDetails()
    {
        RequestHandler::handle(new GemeindeDetailsAction());
    }

    public static function zeigeGemeindeListe()
    {
        RequestHandler::handle(new GemeindeListeAction());
    }

    public static function exportGemeindeListeExcel()
    {
        RequestHandler::handle(new GemeindeListeExcel());
    }
}
?>