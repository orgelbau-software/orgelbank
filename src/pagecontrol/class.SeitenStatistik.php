<?php

class SeitenStatistik
{

    protected $page;

    protected $description;

    public function __construct($page, $description = "")
    {
        if ($page == null || trim($page) == "")
            throw new Exception("Seitestatistik: Page darf nicht NULL oder *leer* sein.");
        
        $this->page = $page;
        $this->description = $description;
    }

    public static function count($page, $description = "")
    {
        $p = new SeitenStatistik($page, $description);
        if (! $p->exists()) {
            $p->addPage();
        }
        $p->addCall();
    }

    public function exists()
    {
        $sql = "SELECT * FROM seitenstatistik WHERE ss_url = '" . $this->page . "'";
        return DB::getInstance()->getMysqlNumRows($sql) >= 1;
    }

    public function addPage()
    {
        $sql = "INSERT INTO seitenstatistik VALUES('" . $this->page . "', '" . $this->description . "', 0)";
        DB::getInstance()->NonSelectQuery($sql);
    }

    public function addCall()
    {
        $sql = "UPDATE seitenstatistik SET ss_count = ss_count + 1 WHERE ss_url = '" . $this->page . "'";
        DB::getInstance()->NonSelectQuery($sql);
    }
}
?>