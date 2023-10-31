<?php
class MitarbeiterStundenzettelAction implements GetRequestHandler {
    
    protected $benutzerId;
    protected $jahr;
    
    /**
     * {@inheritDoc}
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        return isset($_GET['bid']);
    }

    /**
     * {@inheritDoc}
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
        
    }

    /**
     * {@inheritDoc}
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        $this->benutzerId = intval($_GET['bid']);
        $this->jahr = isset($_GET['jahr']) ? intval($_GET['jahr']) : null;
    }

    /**
     * {@inheritDoc}
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $data = ArbeitswocheUtilities::ladeArbeitswochenByBenutzerId($this->benutzerId, $this->jahr);
        $benutzer = new Benutzer($this->benutzerId);
        
        
        $pdf = new StundenzettelPDF();
        $pdf->AliasNbPages();
        $pdf->printData($benutzer, $data);
        return $pdf;
    }

    
}