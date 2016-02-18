<?php
/**
 * Magento
 *
 */

/* AITOC static rewrite inserts start */
/* $meta=%default,AdjustWare_Giftreg% */
if(Mage::helper('core')->isModuleEnabled('AdjustWare_Giftreg')){
    class Aitoc_Aitcheckoutfields_Block_Rewrite_FrontCheckoutOnepageShipping_Aittmp extends AdjustWare_Giftreg_Block_Rewrite_FrontCheckoutOnepageShipping {} 
 }else{
    /* default extends start */
    class Aitoc_Aitcheckoutfields_Block_Rewrite_FrontCheckoutOnepageShipping_Aittmp extends Mage_Checkout_Block_Onepage_Shipping {}
    /* default extends end */
}

/* AITOC static rewrite inserts end */
class Aitoc_Aitcheckoutfields_Block_Rewrite_FrontCheckoutOnepageShipping extends Aitoc_Aitcheckoutfields_Block_Rewrite_FrontCheckoutOnepageShipping_Aittmp
{
    
    protected function _construct()
    {
        parent::_construct();
    }
    
    public function getFieldHtml($aField)
    {
        $sSetName = 'shipping';
        
        return Mage::getModel('aitcheckoutfields/aitcheckoutfields')->getAttributeHtml($aField, $sSetName, 'onepage');
    }
    
    public function getCustomFieldList($iTplPlaceId)
    {
        $iStepId = Mage::helper('aitcheckoutfields')->getStepId('shippinfo');
        
        if (!$iStepId) return false;

        return Mage::getModel('aitcheckoutfields/aitcheckoutfields')->getCheckoutAttributeList($iStepId, $iTplPlaceId, 'onepage');
    } 
}