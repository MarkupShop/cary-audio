<?php
/**
 * Magento
 *
 */

class Aitoc_Aitcheckoutfields_Block_Rewrite_FrontCheckoutMultishippingAddresses extends Mage_Checkout_Block_Multishipping_Addresses
{
    public function _prepareLayout()
    {
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setCanLoadCalendarJs(true);
        }
        
        return parent::_prepareLayout();
    }
    
    public function getFieldHtml($aField)
    {
        $sSetName = 'multi';
        
        return Mage::getModel('aitcheckoutfields/aitcheckoutfields')->getAttributeHtml($aField, $sSetName, 'multishipping');
    }
    
    public function getCustomFieldList($iTplPlaceId)
    {
        $iStepId = Mage::helper('aitcheckoutfields')->getStepId('mult_addresses');
        
        if (!$iStepId) return false;

        return Mage::getModel('aitcheckoutfields/aitcheckoutfields')->getCheckoutAttributeList($iStepId, $iTplPlaceId, 'multishipping');
    } 
}