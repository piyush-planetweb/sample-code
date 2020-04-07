<?php
namespace Pws\Panel\Controller\Panel;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
class Post extends \Pws\Panel\Controller\Index
{
	/**
     * @var \Magento\Framework\Filesystem
     */
	
    protected $_fileSystem;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Filesystem $filesystem
     */
	
	protected $_panelHelper;
    /**
     * @param Action\Context $context
     */

   
    public function execute()
    {
    	$data = $this->getRequest()->getPostValue();


        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
			//$data['image'] = date("mdYHis").$data['url_key'].$data['image'];
            $model = $this->_objectManager->create('Pws\Panel\Model\Panel');
            $id = $this->getRequest()->getParam('panel_id');
            $panel_image = $panel_thumbnail = "";
            if ($id) {
                $model->load($id);
                $panel_image = $model->getImage();
                $panel_thumbnail = $model->getThumbnail();
            }else{
				$name = $data['name'];
				$cusid = $data['url_key'];
				$collection = $model->getCollection()->addFieldToFilter('name', $name)
        ->addFieldToFilter('url_key', $cusid);
				if(count($collection)>1){
					$this->messageManager->addError('Same Name project already exist. Please try again with different Project Name.');
					return $resultRedirect->setPath('*/*/add');
				}
				
				//$this->_panelHelper->getPanelListbynamecus($name,$cusid);
			}
			

            /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
            $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
            ->getDirectoryRead(DirectoryList::MEDIA);
            $mediaFolder = 'pws/panel/';
            $path = $mediaDirectory->getAbsolutePath($mediaFolder);

            // Delete, Upload Image
            $imagePath = $mediaDirectory->getAbsolutePath($model->getImage());
            if(isset($data['image']['delete']) && file_exists($imagePath)){
                unlink($imagePath);
                $data['image'] = '';
                if($panel_image && $panel_thumbnail && $panel_image == $panel_thumbnail){
                    $data['thumbnail'] = '';
                }
            }
            if(isset($data['image']) && is_array($data['image'])){
                unset($data['image']);
            }
            if($image = $this->uploadImage('image')){
                $data['image'] = $image;
            }

            // Delete, Upload Thumbnail
            $thumbnailPath = $mediaDirectory->getAbsolutePath($model->getThumbnail());
            if(isset($data['thumbnail']['delete']) && file_exists($thumbnailPath)){
                unlink($thumbnailPath);
                $data['thumbnail'] = '';
                if($panel_image && $panel_thumbnail && $panel_image == $panel_thumbnail){
                    $data['image'] = '';
                }
            }
            if(isset($data['thumbnail']) && is_array($data['thumbnail'])){
                unset($data['thumbnail']);
            }
            if($thumbnail = $this->uploadImage('thumbnail',$data['url_key'])){
                $data['thumbnail'] = $thumbnail;
            }

            $model->setData($data);
            try {
				
                $model->save();
				$mailsend = 0;
				if(isset($data['mail_send'])){
					if($data['mail_send']){
					
					$scopeConfig =  $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
					$fromname = $scopeConfig->getValue('trans_email/ident_sales/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
					$fromemail = $scopeConfig->getValue('trans_email/ident_sales/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
					$adminemail = $scopeConfig->getValue('sales_email/order/copy_to',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
					$username = $useremail ='';
					$customerSession = $this->_objectManager->create('Magento\Customer\Model\Session');
					if($customerSession->isLoggedIn()) {
						$username =  $customerSession->getCustomer()->getName();
						$useremail = $customerSession->getCustomer()->getEmail();
					}
					
					$templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' =>$this->storeManager->getStore()->getId());
					$templateVars = array(
										'store' => $this->storeManager->getStore(),
										'cname' => $username,
										'cemail' => $useremail,
									);
					$from = [
                    'name' => 'Perfect Panel',
                    'email' => $fromemail,
                    ];
					$this->inlineTranslation->suspend();
					$to = array($adminemail);
					$transport = $this->_transportBuilder->setTemplateIdentifier(4)
									->setTemplateOptions($templateOptions)
									->setTemplateVars($templateVars)
									->setFrom($from)
									->addTo($to)
									->getTransport();
					$transport->sendMessage();
					
					$touser = array($useremail);
					$transportnew = $this->_transportBuilder->setTemplateIdentifier(3)
									->setTemplateOptions($templateOptions)
									->setTemplateVars($templateVars)
									->setFrom($from)
									->addTo($useremail)
									->getTransport();
					$transportnew->sendMessage();
					$this->inlineTranslation->resume();
					$mailsend = 1;
					
					
					
					
				}
				}
				if($mailsend){
					$this->messageManager->addSuccess(__('Your Quote has been submitted successfully.'));
				}else{
					$this->messageManager->addSuccess(__('You saved this project.'));
				}
                //$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/add', ['panel_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('projects.html');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the project.'));
            }
            //$this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/add', ['panel_id' => $this->getRequest()->getParam('panel_id')]);
        }
        return $resultRedirect->setPath('*/*/add');
    }
	
	public function uploadImage($fieldId = 'image')
    {

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (isset($_FILES[$fieldId]) && $_FILES[$fieldId]['name']!='') 
        {
			$_FILES[$fieldId]['name'] = date("mdYHis").$_FILES[$fieldId]['name'];
			$_FILES[$fieldId]['name'] = str_replace(' ','_',$_FILES[$fieldId]['name']);
            $uploader = $this->_objectManager->create(
                'Magento\Framework\File\Uploader',
                array('fileId' => $fieldId)
                );
            $path = $this->_objectManager->get('Magento\Framework\Filesystem')->getDirectoryRead(
                DirectoryList::MEDIA
                )->getAbsolutePath(
                'catalog/category/'
                );


                /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                ->getDirectoryRead(DirectoryList::MEDIA);
                $mediaFolder = 'pws/panel/';
                try {
                    $uploader->setAllowedExtensions(array('dxf','dwg','iges','ai','pdf','stp'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $result = $uploader->save($mediaDirectory->getAbsolutePath($mediaFolder)
                        );
                    return $mediaFolder.$result['name'];
                } catch (\Exception $e) {
                    //$this->_logger->critical($e);
                    $this->messageManager->addError($e->getMessage());
                    return $resultRedirect->setPath('*/*/edit', ['panel_id' => $this->getRequest()->getParam('panel_id')]);
                }
            }
            return;
        }
}
