<?php
/**
 * @copyright  Copyright (c) 2009 AITOC, Inc. 
 */
class Aitoc_Aitcheckoutfields_Model_Rewrite_FrontCustomerCustomer extends Mage_Customer_Model_Customer
{
    protected $_cfmCustomFields;

    protected function _beforeSave()
    {
        $oReq = Mage::app()->getFrontController()->getRequest();
        
        $data = $oReq->getPost('aitreg');
        
        if($data && !Mage::registry('aitoc_customer_saved') && !Mage::registry('aitoc_customer_to_save'))
        {
            $oAttribute = Mage::getModel('aitcheckoutfields/aitcheckoutfields');
            foreach ($data as $sKey => $sVal)
            {
                $oAttribute->setCustomValue($sKey, $sVal, 'register');
            }
            Mage::register('aitoc_customer_to_save', true);
        }
         
        return parent::_beforeSave();
    }
    protected function _afterSave()
    {
        $oAttribute = Mage::getModel('aitcheckoutfields/aitcheckoutfields');
        if(Mage::registry('aitoc_customer_to_save') && !Mage::registry('aitoc_customer_saved'))
        {
            $customerId = $this->getId();
            $oAttribute->saveCustomerData($customerId);
            $oAttribute->clearCheckoutSession('register');
            Mage::unregister('aitoc_customer_to_save');
            Mage::register('aitoc_customer_saved', true);
            
            Mage::dispatchEvent('aitcfm_customer_save_after', array('customer' => $this, 'checkoutfields' => $this->getCustomFields()));
        }
        $oAttribute->clearCheckoutSession('register');
        return parent::_afterSave();       
    }
    
    /**
     * Get CFM data for this customer
     * 
     * @param bool $forceReload Forces tranport object to be reloaded. Default: false.
     * 
     * @return Aitoc_Aitcheckoutfields_Model_Transport
     */    
    public function getCustomFields($forceReload = false)
    {
        if(is_null($this->_cfmCustomFields) || $forceReload)
        {
            $this->_cfmCustomFields = Mage::getModel('aitcheckoutfields/transport')->loadByCustomer($this);
        }
        return $this->_cfmCustomFields;
    }
}