<?php
class MitarbeiterStundenzettelAction implements GetRequestHandler {
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
        $_GET['bid'] = intval($_GET['bid']);
    }

    /**
     * {@inheritDoc}
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $data = ArbeitswocheUtilities::ladeArbeitswochenByBenutzerId($_GET['bid']);
        $benutzer = new Benutzer($_GET['bid']);
        
        
        $pdf = new StundenzettelPDF();
        $pdf->AliasNbPages();
        $pdf->printData($benutzer, $data);
        return $pdf;
    }

    
}