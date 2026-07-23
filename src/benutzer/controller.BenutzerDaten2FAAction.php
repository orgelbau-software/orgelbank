<?php
use OTPHP\TOTP;

class BenutzerDaten2FAAction implements GetRequestHandler, PostRequestHandler
{
    #[Override]
    public function executeGet()
    {
        global $webUser;
        
        if (! $webUser->isAuthed()) {
            die("Forbidden!");
        }
        
        $tpl = new Template("benutzer_2faaktivierung.tpl");
        $clock = new OrgelbankClock();
        
        $htmlStatus = null;
        if($_POST) {
            $secret = $_POST['secret'];

            $otp = TOTP::createFromSecret($secret, $clock);
            //echo "The current OTP is: {$otp->now()}\n";

            $theOTP = $otp->now();
            if($_POST['otp'] == $theOTP) {
                $webUser->getBenutzer()->set2FAAktiv(Benutzer::$ZWEIFAKTOR_STATUS_AKTIVIERT); //aktiv
                $webUser->getBenutzer()->set2FASecret($secret); // secret
                $webUser->getBenutzer()->speichern(true);

                $htmlStatus = new HTMLStatus("OTP stimmt. 2-FA aktiviert. Sie müssen sich jetzt neu anmelden.", HTMLStatus::$STATUS_OK);
            } else {
                $htmlStatus = new HTMLStatus("OTP stimmt nicht. Bitte nochmal.", HTMLStatus::$STATUS_ERROR);
            }

        } else {
            $otp = TOTP::generate($clock);
        }

        // Note: use your own way to load the user secret.
        // The function "load_user_secret" is simply a placeholder.
        //$secret = "load_user_secret()";
        //$secret = "K7TG6FQIK2PH3B7A27LEXH7WFO2HZPAEDWSEMB4ERCXPL2UYZQOLYDBI57MEH6TKZJPFLBZP7ZXACT2Q66BO24UY7HE2EH3KOLZN5LA";
        //$otp = TOTP::createFromSecret($secret, $clock);
        //$otp = TOTP::createFromSecret($secret, $clock);
        //echo "The current OTP is: {$otp->now()}\n";

        $otp = $otp->withLabel(INSTALLATION_NAME);
        //$grCodeUri = $otp->getQrCodeUri(
        //    'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
        //    '[DATA]'
        //);

        $grCodeUri = $otp->getQrCodeUri(
            INSTANCE_URL. 'lib/qrcode/qrcode.php?data=[DATA]&size=300x300&ecc=M',
            '[DATA]'
        );


        $tpl->replace("QRCodeImageURL", $grCodeUri);
        $tpl->replace("Secret", $otp->getSecret());

        if($htmlStatus != null) {
            $tpl->replace("Statusmeldung", $htmlStatus->getOutput());
        }

        return $tpl;
    }

    #[Override]
    public function preparePost()
    {
        // Nothing to Do
    }

    #[Override]
    public function executePost()
    {
        return $this->executeGet();
    }
    #[Override]
    public function validateGetRequest()
    {
        return true;
    }

    #[Override]
    public function handleInvalidGet()
    {
        return new HTMLStatus("Ungueltige Anfrage.", 4);
    }

    #[Override]
    public function prepareGet()
    {
        // Nothing
    }

    
    
}