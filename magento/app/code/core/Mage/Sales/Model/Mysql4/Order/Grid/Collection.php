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
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Flat sales order grid collection
 *
 */
class Mage_Sales_Model_Mysql4_Order_Grid_Collection extends Mage_Sales_Model_Mysql4_Order_Collection
{
    protected $_eventPrefix = 'sales_order_grid_collection';
    protected $_eventObject = 'order_grid_collection';

    /**
     * Customer mode flag
     * 
     * @var bool
     */
    protected $_customerModeFlag = false;

    protected function _construct()
    {
        parent::_construct();
        $this->setMainTable('sales/order_grid');
    }

    /**
     * Get SQL for get record count
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $unionSelect = clone $this->getSelect();

        $unionSelect->reset(Zend_Db_Select::ORDER);
        $unionSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $unionSelect->reset(Zend_Db_Select::LIMIT_OFFSET);

        $countSelect = clone $this->getSelect();
        $countSelect->reset();
        $countSelect->from(array('a' => $unionSelect), 'COUNT(*)');

        return $countSelect;
    }

    /**
     * Set customer mode flag value
     *
     * @param bool $value
     * @return Mage_Sales_Model_Mysql4_Order_Grid_Collection
     */
    public function setIsCustomerMode($value)
    {
        $this->_customerModeFlag = (bool)$value;
        return $this;
    }

    /**
     * Get customer mode flag value
     *
     * @return bool
     */
    public function getIsCustomerMode()
    {
        return $this->_customerModeFlag;
    }
}
