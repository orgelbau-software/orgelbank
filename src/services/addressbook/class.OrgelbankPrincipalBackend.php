<?php
use services\addressbook\FileLogger;

class OrgelbankPrincipalBackend extends Sabre\DAVACL\PrincipalBackend\AbstractBackend implements Sabre\DAVACL\PrincipalBackend\BackendInterface
{

    /**
     *
     * @var FileLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = new FileLogger();
        $this->logger->log("init");
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\PrincipalBackend\BackendInterface::getPrincipalsByPrefix()
     */
    public function getPrincipalsByPrefix($prefixPath)
    {
        $this->logger->log("getPrincipalsByPrefix: " . $prefixPath);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\PrincipalBackend\BackendInterface::getPrincipalByPath()
     */
    public function getPrincipalByPath($path)
    {
        $this->logger->log("getPrincipalByPath: " . $path);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\PrincipalBackend\BackendInterface::updatePrincipal()
     */
    public function updatePrincipal($path, \Sabre\DAV\PropPatch $propPatch)
    {
        $this->logger->log("updatePrincipal: " . $path . ",  " . $propPatch);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\PrincipalBackend\BackendInterface::searchPrincipals()
     */
    public function searchPrincipals($prefixPath, array $searchProperties, $test = 'allof')
    {
        $this->logger->log("searchPrincipals: " . $prefixPath);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\PrincipalBackend\BackendInterface::findByUri()
     */
    public function findByUri($uri, $principalPrefix)
    {
        $this->logger->log("findByUri: " . $uri);
        return parent::findByUri($uri, $principalPrefix);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\PrincipalBackend\BackendInterface::getGroupMemberSet()
     */
    public function getGroupMemberSet($principal)
    {
        $this->logger->log("getGroupMemberSet: " . $principal);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\PrincipalBackend\BackendInterface::getGroupMembership()
     */
    public function getGroupMembership($principal)
    {
        $this->logger->log("getGroupMembership: " . $principal);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\PrincipalBackend\BackendInterface::setGroupMemberSet()
     */
    public function setGroupMemberSet($principal, array $members)
    {
        $this->logger->log("setGroupMemberSet: " . $principal);
    }
}

?>