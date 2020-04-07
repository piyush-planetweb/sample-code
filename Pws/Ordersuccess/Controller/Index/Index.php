<?php
namespace Pws\Ordersuccess\Controller\Index;

use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {


         $this->_view->loadLayout();

        $this->_view->renderLayout();

    }
	
}