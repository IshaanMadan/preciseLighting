<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Setup;

use Magento\Catalog\Model\Category;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

/**
 * Class InstallData
 * @package Magenest\QuickBooksDesktop\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qbd_edit_sequence_product'
        );
        $eavSetup->removeAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'list_qbd_id_product'
        );
        $eavSetup->removeAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'qbd_edit_sequence_customer'
        );
        $eavSetup->removeAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'list_qbd_id_customer'
        );
        
        // add attributes for product
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qbd_edit_sequence_product',
            [
                'group' => 'Product Details',
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'sort_order' => 50,
                'label' => 'Qbd Edit Sequence Product',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => false,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to'=>'simple,configurable,bundle,grouped'
            ]
        )->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'list_qbd_id_product',
            [
                'group' => 'Product Details',
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'sort_order' => 50,
                'label' => 'List Qbd Id Product',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => false,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to'=>'simple,configurable,bundle,grouped'
            ]
        );
        // add attributes for customer
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'list_qbd_id_customer',
            [
            'type'             => 'varchar',
            'label'            => 'List Qbd Id Customer',
            'visible'          => false,
            'required'         => 0,
            'system'           => 0,
            'position'         => 1000
            ]
        );
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'qbd_edit_sequence_customer',
            [
            'type'             => 'varchar',
            'label'            => 'Qbd Edit Sequence Customer',
            'visible'          => false,
            'required'         => 0,
            'system'           => 0,
            'position'         => 1100
            ]
        );

        $attribute = $customerSetup->getEavConfig()
            ->getAttribute(Customer::ENTITY, 'list_qbd_id_customer')
            ->addData(
                [
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer'],
                ]
            );
        
        $attribute->save();
        
        $attribute = $customerSetup->getEavConfig()
            ->getAttribute(Customer::ENTITY, 'qbd_edit_sequence_customer')
            ->addData(
                [
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer'],
                ]
            );
        $attribute->save();
        $setup->endSetup();
    }
}
