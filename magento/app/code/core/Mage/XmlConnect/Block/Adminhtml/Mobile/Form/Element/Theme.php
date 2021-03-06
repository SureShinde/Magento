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
 * @package     Mage_XmlConnect
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

class Mage_XmlConnect_Block_Adminhtml_Mobile_Form_Element_Theme extends Varien_Data_Form_Element_Text
{
    /**
     * Generate themes (colors) html
     *
     * @return string
     */
    public function getHtml()
    {
        $blockClassName = Mage::getConfig()->getBlockClassName('xmlconnect/adminhtml_mobile_edit_tab_design_themes');
        $block = new $blockClassName;
        $block->setThemes($this->getThemes());
        $block->setName($this->getName());
        $block->setValue($this->getValue());
        return $block->toHtml();
    }
}
