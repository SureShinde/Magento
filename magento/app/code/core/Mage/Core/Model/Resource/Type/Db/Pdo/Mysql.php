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
 * @package     Mage_Core
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


class Mage_Core_Model_Resource_Type_Db_Pdo_Mysql extends Mage_Core_Model_Resource_Type_Db
{

    /**
     * Enter description here...
     *
     * @param array $config Connection config
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    public function getConnection($config)
    {
        $configArr = (array)$config;
        $configArr['profiler'] = !empty($configArr['profiler']) && $configArr['profiler']!=='false';

        $conn = $this->_getDbAdapterInstance($configArr);

        if (!empty($configArr['initStatements']) && $conn) {
            $conn->query($configArr['initStatements']);
        }

        return $conn;
    }

    /**
     * Create and return DB adapter object instance
     *
     * @param array $configArr Connection config
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getDbAdapterInstance($configArr)
    {
        $className = $this->_getDbAdapterClassName();
        $adapter = new $className($configArr);
        return $adapter;
    }

    /**
     * Retrieve DB adapter class name
     *
     * @return string
     */
    protected function _getDbAdapterClassName()
    {
        return 'Varien_Db_Adapter_Pdo_Mysql';
    }

}
