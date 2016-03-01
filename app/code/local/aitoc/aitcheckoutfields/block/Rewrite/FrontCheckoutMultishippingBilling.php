<?php
/**
 * Magento
 *
 */

class Aitoc_Aitcheckoutfields_Block_Rewrite_FrontCheckoutMultishippingBilling extends Mage_Checkout_Block_Multishipping_Billing
{
    
    // overwright parent
    protected function _construct()
    {
        parent::_construct();
    }
    
    public function getFieldHtml($aField)
    {
        $sSetName = 'multi';
        
        return Mage::getModel('aitcheckoutfields/aitcheckoutfields')->getAttributeHtml($aField, $sSetName, 'multishipping');
    }
    
    public function getAttributeEnableHtml($aField)
    {
        $sSetName = 'multi';
        
        return Mage::getModel('aitcheckoutfields/aitcheckoutfields')->getAttributeEnableHtml($aField, $sSetName);
    }
    
    public function getCustomFieldList($iTplPlaceId)
    {
        $iStepId = Mage::helper('aitcheckoutfields')->getStepId('mult_billing');
        
        if (!$iStepId) return false;

        return Mage::getModel('aitcheckoutfields/aitcheckoutfields')->getCheckoutAttributeList($iStepId, $iTplPlaceId, 'multishipping');
    } 
}