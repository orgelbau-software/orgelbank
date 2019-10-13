<?php
use services\addressbook\FileLogger;

class OrgelbankAddressbookBackend extends Sabre\CardDAV\Backend\AbstractBackend implements Sabre\CardDAV\Backend\SyncSupport
{

    /**
     *
     * @var FileLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = new FileLogger();
    }

    public function getAddressBooksForUser($principalUri)
    {
        $this->logger->log(__CLASS__, __FUNCTION__);
    }

    public function updateAddressBook($addressBookId, \Sabre\DAV\PropPatch $propPatch)
    {
        $this->logger->log(__CLASS__, __FUNCTION__);
    }

    public function createAddressBook($principalUri, $url, array $properties)
    {
        $this->logger->log(__CLASS__, __FUNCTION__);
    }

    public function deleteAddressBook($addressBookId)
    {
        $this->logger->log(__CLASS__, __FUNCTION__);
    }

    public function getCards($addressbookId)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": " . $addressbookId);
        
        $alleAnsprechpartner = AnsprechpartnerUtilities::getAktiveAnsprechpartner();
        
        $converter = new AnsprechpartnerToVCardConverter();
        $retVal = array();
        foreach ($alleAnsprechpartner as $current) {
            $this->logger->log(__CLASS__, $current);
            $currentVCard = $converter->convert($current);
            $retVal[] = $this->convertAnsprechpartnerToCardData($current, $currentVCard);
        }
        
        return $retVal;
    }

    public function getCard($addressBookId, $cardUri)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . " URI: " . $cardUri);
        
        $id = intval(substr($cardUri, 0, strpos($cardUri, ".")));
        $this->logger->log(__CLASS__, __FUNCTION__ . " ID: " . $id);
        
        $oAnsprechpartner = new Ansprechpartner($id);
        
        $converter = new AnsprechpartnerToVCardConverter();
        $currentVCard = $converter->convert($oAnsprechpartner);
        
        return $this->convertAnsprechpartnerToCardData($oAnsprechpartner, $currentVCard);
    }

    protected function convertAnsprechpartnerToCardData(Ansprechpartner $pAnsprechpartner, \JeroenDesloovere\VCard\VCard $vCard)
    {
        $item = array();
        $item['carddata'] = $vCard->getOutput();
        $item['uri'] = $pAnsprechpartner->getID();
        $item['lastmodified'] = strtotime($pAnsprechpartner->getChangeAt());
        $item['etag'] = $pAnsprechpartner->getID() . "" . strtotime($pAnsprechpartner->getChangeAt());
        $item['etag'] = "x";
        $item['size'] = strlen($item['carddata']);
        return $item;
    }

    public function createCard($addressBookId, $cardUri, $cardData)
    {
        $this->logger->log(__CLASS__, __FUNCTION__);
    }

    public function updateCard($addressBookId, $cardUri, $cardData)
    {
        $this->logger->log(__CLASS__, __FUNCTION__);
    }

    public function deleteCard($addressBookId, $cardUri)
    {
        $this->logger->log(__CLASS__, __FUNCTION__);
    }

    public function getChangesForAddressBook($addressBookId, $syncToken, $syncLevel, $limit = null)
    {
        $this->logger->log(__CLASS__, __FUNCTION__);
    }
}

?>