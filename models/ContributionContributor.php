<?php
/**
 * @version $Id$
 * @author CHNM
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2010
 * @package Contribution
 */

class ContributionContributor extends Omeka_Record
{
    public $name;
    public $email;
    public $ip_address;
    
    protected $_related = array('Items' => 'getContributedItems');
    
    protected function _initializeMixins()
    {
        $this->mixins[] = new ActsAsElementText($this);
        $this->mixins[] = new Relatable($this);
    }
    
    /**
     * Validate form submissions.
     * Gotta have a valid 1) ip address, 2) email address, 3) first & last name
     */
    protected function _validate()
    {       
        if(empty($this->ip_address)) {
            $this->addError('ip_address', 'Contributors must come from a valid IP address.');
        }
        
        if(!Zend_Validate::is($this->email, 'EmailAddress')) {
            $this->addError('email', 'The email address you have provided is invalid.  Please provide another one.');
        }
        
        if(empty($this->first_name) or empty($this->last_name)) {
            $this->addError('name', 'The first/last name fields must be filled out.  Please provide a complete name.');     
        }
    }
    
    /**
     * Called before validation
     * If the contributor is a new entry, then pull in the IP address of the browser before saving
     */
    protected function beforeValidate()
    {
        if(empty($this->ip_address) and !$this->exists()) {
            $this->setDottedIpAddress($_SERVER['REMOTE_ADDR']);
        }
    }
    
    /**
     * Return the items that the contributor has contributed.
     * @todo actually implement
     */
    public function getContributedItems()
    {
        
    }
    
    /**
     * Gets a standard-format IP address from the internal
     * integer representation.
     *
     * @return string
     */
    public function getDottedIpAddress()
    {
        if (!($ipAddress = $this->ip_address)) {
            return null;
        }
        return long2ip($ipAddress);
    }
    
    /**
     * Sets an IP dotted-quad address on the Contributor.
     * Converts to a long in the process.
     *
     * @param string $dottedIpAddress A
     */
    public function setDottedIpAddress($dottedIpAddress)
    {
        $this->ip_address = ip2long($dottedIpAddress);
    }
}