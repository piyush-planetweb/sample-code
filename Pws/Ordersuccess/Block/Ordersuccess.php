<?php
namespace Pws\Ordersuccess\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;

class Ordersuccess extends Template
{
	public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

	public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->logger = $this->objectManager->get('\Psr\Log\LoggerInterface');
    }
    
}