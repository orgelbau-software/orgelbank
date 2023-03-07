<?php

class OrgelGemeindeVerbindungLoeschen implements GetRequestHandler
{

    /**
     *
     * {@inheritdoc}
     *
     * @return bool
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return HTMLStatus
     */
    public function handleInvalidGet()
    {
        return new HTMLStatus("Alles ok");
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function prepareGet()
    {
        return;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return Template
     */
    public function executeGet()
    {
        if (! isset($_GET['oid'], $_GET['gid']))
            return;
        
        OrgelUtilities::deleteOrgelGemeindeLink($_GET['oid'], $_GET['gid']);
        
        $tplStatus = new Template("status_zurueck_u_redirect.tpl");
        $tplStatus->replace("Text", "Orgelverbindung wurde gel&ouml;scht.");
        $tplStatus->replace("Sekunden", 1);
        $tplStatus->replace("Ziel", "index.php?page=2&do=21&oid=" . $_GET['oid']);
        
        return $tplStatus;
    }
}