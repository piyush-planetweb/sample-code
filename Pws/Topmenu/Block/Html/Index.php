<?php
/**
 * @author Vendor Team
 * @copyright Copyright (c) 2016 Vendor
 * @package Vendor_ModuleName
 */

namespace Pws\Topmenu\Block\Html;

use Magento\Framework\ObjectManagerInterface;


class Index extends \Magento\Framework\View\Element\Template
{
    /**
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_object;
    /**
     *
     * @var \Magento\Framework\Module\Manager $moduleManager
     */
    protected $_moduleManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Customer\Model\Session $customerSession,
        ObjectManagerInterface $interface
    ) {
        $this->_customerSession = $customerSession;
        $this->_object = $interface;
        $this->_moduleManager = $moduleManager;
        parent::__construct($context);
    }

    public function getCustomerSession(){
        $customerSession = $this->_object->create('Magento\Customer\Model\SessionFactory')->create();
        return $customerSession;
    }

    /**
     * Check customer is logged in or not
     * @var $_SESSION['customer_base']['customer_id']
     * used to resolve confliction from magento FPC
     * @return boolean
     */
    public function getCustomerLoggedIn()
    {
        $login = false;
        $_customCustomerSession = $this->getCustomerSession();
        $_customerId = $_customCustomerSession->getCustomerId();
        $customerSession = $this->_customerSession;
        if($customerSession->isLoggedIn() || $_customerId) {
            $login = true;
        }

        return $login;
    }

    /**
     * get customer data
     * @return array [customer id,name,email,group]
    */
    public function getCustomerData()
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $_customCustomerSession = $this->getCustomerSession();
        $_customerId = $_customCustomerSession->getCustomerId();

        if($_customerId)
        {
            $_customerId = $_SESSION['customer']['customer_id'];
            $_customer = $om->get('\Magento\Customer\Model\Customer')->load($_customerId);
            $_customerData = array(
                'name' => $_customer->getName(),
                'id' => $_customer->getId(),
                'email' => $_customer->getEmail(),
                'group' => $_customer->getGroupId()
            );
            return $_customerData;
        }
    }

    /**
     * Whether a module is enabled in the configuration or not
     *
     * @param string $moduleName Fully-qualified module name
     * @return boolean
     */
    public function isModuleEnabled($moduleName)
    {
        return $this->_moduleManager->isEnabled($moduleName);
    }

    /**
     * Whether a module output is permitted by the configuration or not
     *
     * @param string $moduleName Fully-qualified module name
     * @return boolean
     */
    public function isOutputEnabled($moduleName)
    {
        return $this->_moduleManager->isOutputEnabled($moduleName);
    }
}
