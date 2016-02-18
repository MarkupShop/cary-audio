<?php
/**
 * @copyright  Copyright (c) 2011 AITOC, Inc. 
 */
class Aitoc_Aitcheckoutfields_Model_Rewrite_PaypalExpressCheckout extends Mage_Paypal_Model_Express_Checkout
{
    public function place($token, $shippingMethodCode = null)
    {        
    	$shippingMethodFromReq = Mage::app()->getRequest()->getPost('shipping_method');
    	
    	if (!$shippingMethodCode && $shippingMethodFromReq)
    	{
    		$this->updateShippingMethod($shippingMethodFromReq);
    	}

        try{
            parent::place($token, $shippingMethodCode);
            $orderId = $this->getOrder() instanceof Mage_Sales_Model_Order ? $this->getOrder()->getId() : null;
        } catch (Exception $e) {
            Mage::logException($e);
            $orderId = null;
        }
		
		$recurringProfiles = $this->getRecurringPaymentProfiles();
		$recurringProfileIds = array();
		foreach($recurringProfiles as $recProfile)
		{
		    $recurringProfileIds[] = $recProfile->getId();
	    }
        Mage::dispatchEvent('aitcheckoutfields_paypal_express_order_place_after',
            array('order_id' => $orderId, 'recurring_profile_ids' => $recurringProfileIds)
        );
    }
}