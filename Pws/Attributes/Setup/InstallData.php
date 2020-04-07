<?php

namespace Pws\Attributes\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        /*if(!$eavSetup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, 'delivery_pincode')) {
            */
               $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'delivery_pincode');
               $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'size');
               $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'sleeve');
        //}
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'size',
            [
                'group' => 'Product Details',
                'type' => 'int',
                'frontend' => '',
                'label' => 'Size',
                'input' => 'select',
                'class' => '',
                'source' => 'Pws\Attributes\Model\Config\Source\Size\Options',
                'frontend_class' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => true,
                'default' => '',
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'option' => [ 
                    'values' => [],
                ],
                'unique' => false,
                'apply_to' => 'simple,configurable'
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'sleeve',
            [
                'group' => 'Product Details',
                'type' => 'int',
                'frontend' => '',
                'label' => 'Sleeve',
                'input' => 'select',
                'class' => '',
                'source' => 'Pws\Attributes\Model\Config\Source\Sleeve\Options',
                'frontend_class' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => true,
                'default' => '',
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'option' => [ 
                    'values' => [],
                ],
                'unique' => false,
                'apply_to' => 'simple'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'delivery_pincode',
            [
                'group' => 'Product Details',
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Delivery Pincode',
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'wysiwyg_enabled' => false,
                'is_html_allowed_on_front' => false,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => 'simple,configurable'
            ]
        );
    }
}
