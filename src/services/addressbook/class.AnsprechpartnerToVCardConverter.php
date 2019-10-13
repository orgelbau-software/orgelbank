<?php
use JeroenDesloovere\VCard\VCard;

class AnsprechpartnerToVCardConverter
{

    /**
     *
     * @param Ansprechpartner $pAnsprechpartner            
     * @return \JeroenDesloovere\VCard\VCard
     */
    public function convert(Ansprechpartner $pAnsprechpartner)
    {
        $vcard = new VCard();
        // define variables
        $firstname = $pAnsprechpartner->getVorname();
        $lastname = $pAnsprechpartner->getNachname();
        $additional = '';
        $prefix = '';
        $suffix = '';
        // add personal data
        $vcard->addName($lastname, $firstname, $additional, $prefix, $suffix);
        // add work data
        // $vcard->addCompany();
        $vcard->addJobtitle($pAnsprechpartner->getFunktion());
        $vcard->addEmail($pAnsprechpartner->getEmail());
        $vcard->addPhoneNumber($pAnsprechpartner->getTelefon(), 'PREF;WORK');
        $vcard->addPhoneNumber($pAnsprechpartner->getMobil(), 'WORK');
        // $vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
        // $vcard->addURL($pAnsprechpartner->get);
        // $vcard->addPhoto(__DIR__ . '/assets/landscape.jpeg');
        // $vcard->addPhoto('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg');
        // return vcard as a string
        // return $vcard->getOutput();
        // return vcard as a download
        return $vcard;
    }
}

?>