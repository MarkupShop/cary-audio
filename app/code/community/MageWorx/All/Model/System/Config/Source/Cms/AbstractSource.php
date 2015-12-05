<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_All
 * @copyright  Copyright (c) 2015 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * MageWorx All extension
 *
 * @category   MageWorx
 * @package    MageWorx_All
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

abstract class MageWorx_All_Model_System_Config_Source_Cms_AbstractSource
{
    protected $options;

    abstract protected function getModel();

    public function toOptionArray($isMultiselect=false)
    {
        if (!$this->options) {
            $collection = $this->getModel()->getCollection();
            $collection->addFieldToFilter('is_active', array('eq' => 1));
            if (!Mage::app()->isSingleStoreMode()) {
                $collection->addStoreFilter($this->getConfigStore());
            }

            $this->options = $collection->loadData()->toOptionArray(false);
        }

        $options = $this->options;
        if(!$isMultiselect){
            array_unshift($options, array('value'=>'', 'label'=> Mage::helper('adminhtml')->__('--Please Select--')));
        }

        return $options;
    }
    
    /**
     * Get configuration store. 
     * Website is not checked. Configuration settings which use this class as 
     * source model should not be visible in Website scope.
     *
     * @return int|Mage_Core_Model_Store Selected store or "All Stores" (Default Config)
     */
    protected function getConfigStore()
    {
        $storeCode = Mage::app()->getRequest()->getParam('store');

        $store = 0;
        if ($storeCode) {
            $store = Mage::app()->getStore($storeCode);
        }
        
        return $store;
    }
}