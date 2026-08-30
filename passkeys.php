<?php
declare(strict_types=1);


include_once './vendor/autoload.php';

use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialDescriptor;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\PublicKeyCredential;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorAssertionResponseValidator;

header("Content-Type: application/json");

session_start();


if(!isset($_GET['action'])) {
    echo "error";
} else if ($_GET['action'] == "generate-registration-options") {
    
    $challenge = random_bytes(32);

    $_SESSION['authentication_challenge'] = $challenge;


    $allowedCredentials = PublicKeyCredentialUserEntity::create("stephan.watermeyer","stephan.watermeyer", "Stephan Watermeyer");

    /*foreach ($userPasskeys as $passkey) {

        $allowedCredentials[] =
            new PublicKeyCredentialDescriptor(
                'public-key',
                $passkey->credentialId()
            );
    }*/
    // RP Entity i.e. the application
    $rpEntity = PublicKeyCredentialRpEntity::create(
        'localhost', //Name
        'localhost',              //ID
        null                            //Icon
    );

    $publicKeyCredentialCreationOptions =
        PublicKeyCredentialCreationOptions::create(
            $rpEntity,
            $allowedCredentials,
            $challenge
        )
    ;

    $_SESSION['attestation'] = $publicKeyCredentialCreationOptions;

    // The manager will receive data to load and select the appropriate 
    $attestationStatementSupportManager = AttestationStatementSupportManager::create();

    $factory = new WebauthnSerializerFactory($attestationStatementSupportManager);
    $serializer = $factory->create();

    // The serializer is the same as the one created in the previous pages
    $jsonObject = $serializer->serialize(
        $publicKeyCredentialCreationOptions,
        'json',
        [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true, // Highly recommended!
            JsonEncode::OPTIONS => JSON_THROW_ON_ERROR, // Optional
        ]
    );

    $_SESSION['attestation'] = $publicKeyCredentialCreationOptions;

    echo $jsonObject;

} else if($_GET['action'] == "verify-registration") {
    $attestationStatementSupportManager = AttestationStatementSupportManager::create();
    $factory = new WebauthnSerializerFactory($attestationStatementSupportManager);
    $serializer = $factory->create();

    $data = file_get_contents("php://input");

    $publicKeyCredential = $serializer->deserialize(
        $data,
        PublicKeyCredential::class,
        'json'
    );

    if (!$publicKeyCredential->response instanceof AuthenticatorAttestationResponse) {
        //e.g. process here with a redirection to the public key creation page. 
        echo "error";
    } else {

        $csmFactory = new CeremonyStepManagerFactory();
        $csmFactory->setAllowedOrigins(['http://localhost',]);

        $creationCSM = $csmFactory->creationCeremony();
        $requestCSM = $csmFactory->requestCeremony();

        $authenticatorAttestationResponseValidator = AuthenticatorAttestationResponseValidator::create(
            $creationCSM
        );
        $authenticatorAssertionResponseValidator = AuthenticatorAssertionResponseValidator::create(
            $requestCSM
        );
        
        $publicKeyCredentialCreationOptions = $_SESSION['attestation'];

        $credentialRecord = $authenticatorAttestationResponseValidator->check(
            (object)$publicKeyCredential->response,
            $publicKeyCredentialCreationOptions,
            'localhost'
        );
        echo "valid";
    }
} else {
    echo "error";
}