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
 * @package     Mage_Cms
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * CMS page collection
 *
 * @category   Mage
 * @package    Mage_Cms
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Mage_Cms_Model_Mysql4_Page_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_previewFlag;

    /**
     * Declare base table and mapping of some fields
     */
    protected function _construct()
    {
        $this->_init('cms/page');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
        $this->_map['fields']['store']   = 'store_table.store_id';
    }

    /**
     * deprecated after 1.4.0.1, use toOptionIdArray()
     * 
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('identifier', 'title');
    }

    /**
     * Returns pairs identifier - title for unique identifiers
     * and pairs identifier|page_id - title for non-unique after first
     * 
     * @return array
     */
    public function toOptionIdArray()
    {
        $res = array();
        $existingIdentifiers = array();
        foreach ($this as $item) {
            $identifier = $item->getData('identifier');

            $data['value'] = $identifier;
            $data['label'] = $item->getData('title');
            if (in_array($identifier, $existingIdentifiers)) {
                $data['value'] .= '|' . $item->getData('page_id');
            }
            else {
                $existingIdentifiers[] = $identifier;
            }

            $res[] = $data;
        }

        return $res;
    }

    public function setFirstStoreFlag($flag = false)
    {
        $this->_previewFlag = $flag;
        return $this;
    }

    protected function _afterLoad()
    {
        if ($this->_previewFlag) {
            $items = $this->getColumnValues('page_id');
            if (count($items)) {
                $select = $this->getConnection()->select()
                        ->from($this->getTable('cms/page_store'))
                        ->where($this->getTable('cms/page_store').'.page_id IN (?)', $items);
                if ($result = $this->getConnection()->fetchPairs($select)) {
                    foreach ($this as $item) {
                        if (!isset($result[$item->getData('page_id')])) {
                            continue;
                        }
                        if ($result[$item->getData('page_id')] == 0) {
                            $stores = Mage::app()->getStores(false, true);
                            $storeId = current($stores)->getId();
                            $storeCode = key($stores);
                        } else {
                            $storeId = $result[$item->getData('page_id')];
                            $storeCode = Mage::app()->getStore($storeId)->getCode();
                        }
                        $item->setData('_first_store_id', $storeId);
                        $item->setData('store_code', $storeCode);
                    }
                }
            }
        }

        parent::_afterLoad();
    }

    /**
     * Add filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return Mage_Cms_Model_Mysql4_Page_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }
        $this->addFilter('store', array('in' => ($withAdmin ? array(0, $store) : $store)), 'public');
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('cms/page_store')),
                'main_table.page_id = store_table.page_id',
                array()
            )->group('main_table.page_id');
        }
        return parent::_renderFiltersBefore();
    }

    /**
     * Get SQL for get record count.
     * Extra group by strip added.
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();

        $countSelect->reset(Zend_Db_Select::GROUP);

        return $countSelect;
    }
}
