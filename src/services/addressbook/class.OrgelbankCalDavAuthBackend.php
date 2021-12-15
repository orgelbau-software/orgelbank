<?php
use services\addressbook\FileLogger;

class OrgelbankCalDavAuthBackend extends Sabre\DAV\Auth\Backend\AbstractBasic implements Sabre\DAV\Auth\Backend\BackendInterface
{

    /**
     *
     * @var FileLogger
     */
    protected $logger;

    protected $username;

    public function __construct()
    {
        $this->logger = new FileLogger();
    }

    function validateUserPass($username, $password)
    {
        $this->logger->log(__CLASS__, "validateUserPass: " . $username);
        
        $webUser = new WebBenutzer();
        $webUser->setBenutzername($username);
        $webUser->setPasswort(PasswordUtility::encrypt($password));
        
        if ($webUser != null && $webUser->login()) {
            $this->logger->log(__CLASS__, "login succcessful");
            $this->username = $username;
            return true;
        } else {
            $this->logger->log(__CLASS__, "login failed for " . $username);
        }
        
        return false;
    }

    /**
     * Returns information about the currently logged in username.
     *
     * If nobody is currently logged in, this method should return null.
     *
     * @return string|null
     */
    function getCurrentUser()
    {
        $this->logger->log(__CLASS__, "getCurrentUser: " . $this->username);
        return $this->username;
    }
}

?>