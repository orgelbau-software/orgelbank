<?php

class OrgelController
{

    public static function loescheOrgel()
    {
        RequestHandler::handle(new OrgelLoeschen());
    }

    public static function loescheOrgelGemeindeVerbindung()
    {
        RequestHandler::handle(new OrgelGemeindeVerbindungLoeschen());
    }

    public static function neueOrgelAnlegen()
    {
        RequestHandler::handle(new NeueOrgelAnlegen());
    }

    public static function verwalteOrgelBild()
    {
        RequestHandler::handle(new OrgelBildAction());
    }

    public static function deleteOrgelPicture()
    {
        RequestHandler::handle(new OrgelBildAction());
    }

    public static function speicherOrgelDetails()
    {
        RequestHandler::handle(new OrgelDetailsAction());
    }

    public static function zeigeOrgelDetails()
    {
        RequestHandler::handle(new OrgelDetailsAction());
    }

    /**
     * Zeigt die Orgel Druckansicht
     */
    public static function zeigeOrgelDruckansicht()
    {
        RequestHandler::handle(new OrgelDruckansicht());
    }

    public static function zeigeOrgelListe()
    {
        RequestHandler::handle(new OrgelListeAction());
    }

    public static function zeigeWartungsListe()
    {
        RequestHandler::handle(new WartungsListeAction());
    }

    public static function zeigeOffeneWartungen()
    {
        RequestHandler::handle(new OffeneWartungen());
    }

    public static function exportOrgelListeExcel()
    {
        RequestHandler::handle(new OrgelListeExcel());
    }

    public static function insertOrgelWartung()
    {
        RequestHandler::handle(new OrgelWartungAction());
    }

    public static function zeigeWartungsprotokolle()
    {
        RequestHandler::handle(new WartungsprotokolleAction());
    }
}
?>
