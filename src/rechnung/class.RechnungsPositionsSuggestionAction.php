<?php

class RechnungsPositionsSuggestionAction implements GetRequestHandler
{

    private $term = "";

    public function validateGetRequest()
    {
        return isset($_GET['term']) && strlen($_GET['term']) > 0;
    }

    public function handleInvalidGet()
    {
        $tpl = new Template("content.tpl");
        $tpl->replace("content", "Kein Suchbegriff uebergeben");
        return $tpl;
    }

    public function prepareGet()
    {
        $this->term = addslashes($_GET['term']);
    }

    public function executeGet()
    {
        header("Content-Type: application/json; charset=utf-8");
        $tpl = new Template("content.tpl");
        $content = "";
        
        Log::debug("Suchbegriff: " . $this->term);
        $content .= "[";
        $result = RechnungUtilities::searchRechnungsPositionen($this->term);
        
        $tpl->replace("content", json_encode($result));
        return $tpl;
    }
}
?>