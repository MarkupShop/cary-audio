<?php
/**
 * Magento
 *
 */


class Aitoc_Aitcheckoutfields_Model_Rewrite_FrontCheckoutTypeMultishippingState extends Mage_Checkout_Model_Type_Multishipping_State
{
    
    
    public function setCompleteStep($step)
    {
        $oReq = Mage::app()->getFrontController()->getRequest();
        
        $sKey  = 'multi';
        
        $data = $oReq->getPost($sKey);

        if ($data)
        {
            $oAttribute = Mage::getModel('aitcheckoutfields/aitcheckoutfields');
            
            foreach ($data as $sKey => $sVal)
            {
                $oAttribute->setCustomValue($sKey, $sVal, 'multishipping');
            }
        }
        
        parent::setCompleteStep($step);
    }
    
    
}
