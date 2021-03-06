<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_GoogleCheckout
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

$installer = $this;
/* @var $installer Mage_GoogleCheckout_Model_Mysql4_Setup */

$installer->startSetup();

$installer->updateAttribute('catalog_product', 'disable_googlecheckout', array(
    'attribute_code'    => 'enable_googlecheckout',
    'frontend_label'    => 'Is product available for purchase with Google Checkout',
));

$attribute = $installer->getAttribute('catalog_product', 'enable_googlecheckout');
if (!empty($attribute['attribute_id'])) {
    $installer->run("
        UPDATE `{$installer->getAttributeTable('catalog_product', 'enable_googlecheckout')}`
        SET `value` = ! `value`
        WHERE `attribute_id` = {$attribute['attribute_id']}
    ");
}

$installer->endSetup();
