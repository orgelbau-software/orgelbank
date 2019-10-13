<?php

class OrgelWartungAction implements GetRequestHandler
{

    private $mOrgelId;

    private $mDatum;

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
//         return isset($_GET['oid'], $_GET['datum']);
        return isset($_GET['oid']);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        $this->mOrgelId = intval($_GET['oid']);
//         $this->mDatum = trim($_GET['datum']);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $wartung = new Wartung();
        $wartung->setBemerkung("Eingeplant am ".date("d.m.Y, H:i"). " Uhr");
        $wartung->setOrgelId($this->mOrgelId);
        $wartung->setDatum(date("Y-m-d"));
        $wartung->setChangeBy("system");
        $wartung->speichern(true);
        
        $orgel = new Orgel($this->mOrgelId);
        $orgel->setLetztePflege($wartung->getDatum());
        $orgel->speichern(false);
        
        return new JSONTemplate(array("status" => "ok", "wid" => $wartung->getID()));
    }
}
?>