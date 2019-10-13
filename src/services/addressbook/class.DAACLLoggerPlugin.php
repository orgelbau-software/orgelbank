<?php
use services\addressbook\FileLogger;

class DAACLLoggerPlugin extends Sabre\DAVACL\Plugin
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

    /*
     *
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::beforeBind()
     */
    public function beforeBind($uri)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::beforeBind($uri);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::beforeMethod()
     */
    public function beforeMethod(\Sabre\HTTP\RequestInterface $request, \Sabre\HTTP\ResponseInterface $response)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::beforeMethod($request, $response);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::beforeUnbind()
     */
    public function beforeUnbind($uri)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::beforeUnbind($uri);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::beforeUnlock()
     */
    public function beforeUnlock($uri, \Sabre\DAV\Locks\LockInfo $lock)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": "); // TODO Auto-generated method stub
        return parent::beforeUnlock($uri, $lock);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getACL()
     */
    public function getACL($node)
    {
        // TODO Auto-generated method stub
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        if (is_a($node, "Sabre\\CardDAV\\AddressBook")) {
            $this->logger->log(__CLASS__, "--> " . $node->getName());
        } else {
            $this->logger->log(__CLASS__, "--> " . $node);
        }
        $retVal = parent::getACL($node);
        $this->logger->log(__CLASS__, $retVal);
        return $retVal;
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getCurrentUserPrincipal()
     */
    public function getCurrentUserPrincipal()
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::getCurrentUserPrincipal();
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getCurrentUserPrincipals()
     */
    public function getCurrentUserPrincipals()
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::getCurrentUserPrincipals();
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getCurrentUserPrivilegeSet()
     */
    public function getCurrentUserPrivilegeSet($node)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": " . $node);
        $retVal = parent::getCurrentUserPrivilegeSet($node);
        $this->logger->log(__CLASS__, "-->" . $retVal);
        return $retVal;
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getDefaultSupportedPrivilegeSet()
     */
    public static function getDefaultSupportedPrivilegeSet()
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::getDefaultSupportedPrivilegeSet();
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getFeatures()
     */
    public function getFeatures()
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::getFeatures();
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getMethods()
     */
    public function getMethods($uri)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        // TODO Auto-generated method stub
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getPluginName()
     */
    public function getPluginName()
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::getPluginName();
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getPrincipalByEmail()
     */
    public function getPrincipalByEmail($email)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::getPrincipalByEmail($email);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getPrincipalByUri()
     */
    public function getPrincipalByUri($uri)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::getPrincipalByUri($uri);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getPrincipalMembership()
     */
    public function getPrincipalMembership($mainPrincipal)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": " . $mainPrincipal);
        $retVal = parent::getPrincipalMembership($mainPrincipal);
        return $retVal;
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getSupportedPrivilegeSet()
     */
    public function getSupportedPrivilegeSet($node)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::getSupportedPrivilegeSet($node);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::getSupportedReportSet()
     */
    public function getSupportedReportSet($uri)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": " . $uri);
        return parent::getSupportedReportSet($uri);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::httpAcl()
     */
    public function httpAcl(\Sabre\HTTP\RequestInterface $request, \Sabre\HTTP\ResponseInterface $response)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::httpAcl($request, $response);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::initialize()
     */
    public function initialize(\Sabre\DAV\Server $server)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": " . $server->getBaseUri());
        return parent::initialize($server);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::principalSearch()
     */
    public function principalSearch(array $searchProperties, array $requestedProperties, $collectionUri = null, $test = 'allof')
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::principalSearch($searchProperties, $requestedProperties);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::propFind()
     */
    public function propFind(\Sabre\DAV\PropFind $propFind, \Sabre\DAV\INode $node)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::propFind($propFind, $node);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::propPatch()
     */
    public function propPatch($path, \Sabre\DAV\PropPatch $propPatch)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::propPatch($path, $propPatch);
    }

    /*
     * (non-PHPdoc)
     * @see \Sabre\DAVACL\Plugin::report()
     */
    public function report($reportName, $dom)
    {
        $this->logger->log(__CLASS__, __FUNCTION__ . ": ");
        return parent::report($reportName, $dom);
    }
}