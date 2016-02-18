<?php 
class Aitoc_Aitcheckoutfields_Model_Observer
{
    protected $_isHtmlObserverAllowed = false;
    protected $_isAddressChangeAllowed = false;
    protected $_isAdressChangeObserverExists = false;
    protected $_default_format = false;
    protected $_isMultishipping = false;
    
    public function frontCheckoutMultishippingAddressesPost($object)
    {
         if(!Mage::app()->getRequest()->getParam('continue')) {
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
         }
    }

    public function onPaypalExpressOrderPlaceAfter(Varien_Event_Observer $observer)
    {
        $this->onPaypalExpressOrderPlace($observer);

        $oAttribute = Mage::getModel('aitcheckoutfields/aitcheckoutfields');

        $orderId = $observer->getEvent()->getOrderId();
        if ($orderId) {
            $oAttribute->saveCustomOrderData($orderId, 'onepage');
            $oAttribute->clearCheckoutSession('onepage');
        }

        $recurringProfileIds = $observer->getEvent()->getRecurringProfileIds();
        if (isset($recurringProfileIds) && count($recurringProfileIds) > 0) {
            $oAttribute->saveCustomRecurrentProfileData($recurringProfileIds, 'onepage');
            $oAttribute->clearCheckoutSession('onepage');
        }
    }
    
    public function onPaypalExpressOrderPlace($observer)
    {   
        $data = Mage::app()->getRequest()->getPost('aitpaypalexpress');

        if($data) {
            $mainModel = Mage::getModel('aitcheckoutfields/aitcheckoutfields');
            
            foreach ($data as $key => $value) {
			    $mainModel->setCustomValue($key, $value, 'onepage');
            }
        }
    }

    public function predispatchSagepaysuitePaymentOnepageSaveOrder($observer)
    {
        /* Presaving review CFM fields if payment method was set as sagepay */
        $oReq = Mage::app()->getFrontController()->getRequest();

        $data = $oReq->getPost('customreview');

        if ($data)
        {
            $oAttribute = Mage::getModel('aitcheckoutfields/aitcheckoutfields');

            foreach ($data as $sKey => $sVal)
            {
                $oAttribute->setCustomValue($sKey, $sVal, 'onepage');
            }
        }
    }
    
    public function onPredispatchCheckoutOnepageProgress($observer)
    {
        $this->allowAbstractToHtmlAfter();
        $this->_isAddressChangeAllowed = true;
    }
    
    public function onPredispatchCheckoutMultishipping($observer)
    {
        $this->allowAbstractToHtmlAfter();
        $this->_isAddressChangeAllowed = true;
        $this->_isMultishipping = true;
    }

    public function allowAbstractToHtmlAfter($observer = false)
    {
        $this->_isHtmlObserverAllowed = true;
    }
    
    public function onCoreBlockAbstractToHtmlAfter($observer)
    {
        if(!$this->_isHtmlObserverAllowed) 
        {
            return false;
        }
        $this->_processShippingMethodInfo($observer);
        $this->_processPaymentMethodInfo($observer);
        $this->_processMultishippingMethodInfo($observer);
    }

    public function onCustomerAddressFormat($observer)
    {
        if(!$this->_isAddressChangeAllowed) {
            return false;
        }
        //set flag that observer exists and data was parsed, for 1.4.1.1 compatibility
        $this->_isAdressChangeObserverExists = true;
        $address = $observer->getAddress();
        $type = $address->getAddressType();
        if($this->_isMultishipping)
        {
            $type = 'mult_'.$type;
        }

        $iStepId = Mage::helper('aitcheckoutfields')->getStepId($type);
        if (!$iStepId) return false;
        
        /* Type class is equal for shipping and billing, so we store old value to be able to hide billing fields on shipping step */
        if($this->_default_format) 
        {
            $default_format = $this->_default_format;
            $observer->getType()->setDefaultFormat($default_format);
        } else 
        {
            $default_format = $observer->getType()->getDefaultFormat();
            $this->_default_format = $default_format;
        }

        if($data = $this->_getFieldsText($type)) 
        {
            $observer->getType()->setDefaultFormat(
                $data['top'] . $default_format . '<br />' . $data['bottom']
            );
        }
    }

    /**
     * Event: sales_order_place_after
     * When PayPal Create Order via IPN, copy custom fields from profile to order
     *
     * @param Varien_Event_Observer $observer
     */
    public function salesOrderPlaceAfter($observer)
    {
        /** @var Mage_Sales_Model_Order $order */
        $order = $observer->getEvent()->getOrder();
        if (!$order->getId()) {
            return;
        }
        try{
            $resource        = Mage::getSingleton('core/resource');
            $writeConnection = $resource->getConnection('core_write');
            $select          = $writeConnection->select()
                ->from(
                    array('r' => $resource->getTableName('sales/recurring_profile_order')),
                    array('entity_id' => 'order_id')
                ) // order - profile relations
                ->join(
                    array('pr' => $resource->getTableName('aitcheckoutfields/profile_field')),
                    'r.profile_id = pr.entity_id', array('attribute_id', 'value')
                ) // custom fields in profile
                ->joinLeft(
                    array('o' => $resource->getTableName('aitcheckoutfields/order_field')),
                    'o.entity_id = r.order_id AND o.attribute_id = pr.attribute_id', ''
                ) // custom fields in order, joined for check.
                ->where('r.order_id = ?', $order->getId())
                ->where('o.value_id IS NULL');

            $insert = $select->insertFromSelect(
                $resource->getTableName('aitcheckoutfields/order_field'),
                array('entity_id', 'attribute_id', 'value')
            );
            $writeConnection->query($insert);
        } catch (Exception $e) {
            Mage::logException($e);
        }

    }

    protected function _processShippingMethodInfo($observer)
    {
        if(!$observer->getBlock() instanceof Mage_Checkout_Block_Onepage_Progress) 
        {
            return false;
        }
        if(!$observer->getTransport())
        {
            /** Checking if this version of magento allow us to change html via transport class.
            * Should work fine with any 1.4.1+ magento
            */
            return false;
        }
        if($this->_isMultishipping)
        {
            return false;
        }
        if($data = $this->_getFieldsText('shippmethod')) 
        {
            $html = $observer->getTransport()->getHtml();
            //searching id for link in shipping method and replacing data in next dd block after it
            $result = preg_replace('|#shipping_method(.*)\<dd class="complete"\>(.*)\<\/dd\>|Uis',
                    '#shipping_method$1<dd class="complete">'.$data['top'].'$2<br />'.$data['bottom'].'</dd>'
                    , $html);
            $observer->getTransport()->setHtml($result);
        }
        if(!$this->_isAdressChangeObserverExists)
        {
            /** Compatibility with magento 1.4.1.1 and when 'customer_address_format' event don't exist */
            $types = array('billing', 'shipping');
            foreach($types as $type) 
            {
                if($data = $this->_getFieldsText($type)) 
                {
                    $html = $observer->getTransport()->getHtml();
                    //searching id for link in shipping method and replacing data in next dd block after it
                    $result = preg_replace('|'.$type.'(.*)\<dd class="complete"\>(.*)\<address\>(.*)\<\/address\>(.*)\<\/dd\>|Uis',
                            $type.'$1<dd class="complete"><address>'.$data['top'].'$3<br />'.$data['bottom'].'</address></dd>'
                            , $html);
                    $observer->getTransport()->setHtml($result);
                }
            }
            
        }
    }

    protected function _processMultishippingMethodInfo($observer)
    {
        if(!$this->_isMultishipping)
        {
            return false;
        }
        if(!$observer->getBlock() instanceof Mage_Checkout_Block_Multishipping_Overview)
        {
            return false;
        }
        if(!$observer->getTransport())
        {
            /** Checking if this version of magento allow us to change html via transport class.
            * Should work fine with any 1.4.1+ magento
            */
            return false;
        }
        if($data = $this->_getFieldsText('mult_shippinfo'))
        {
            $html = $observer->getTransport()->getHtml();
            //searching id for link in shipping method and replacing data in next dd block after it
            $result = preg_replace('|backtoshipping(.*)\<div class="box-content"\>(.*)\<\/div\>|Uis',
                    '#backtoshipping$1<div class="box-content">'.$data['top'].'$2<br />'.$data['bottom'].'</div>'
                    , $html);
            $observer->getTransport()->setHtml($result);
        }
    }

    protected function _processPaymentMethodInfo($observer)
    {
        if(!$observer->getBlock() instanceof Mage_Checkout_Block_Onepage_Payment_Info/* && !$observer->getBlock() instanceof  Mage_Checkout_Block_Multishipping_Payment_Info*/)
        {
            return false;
        }
        $type = 'payment';
        /*if($this->_isMultishipping)
        {
            $type = 'mult_billing'; //This is needed if you will need to show mult_billing fields near Payment Options, not in billing address field
        }*/
        if($data = $this->_getFieldsText($type)) 
        {
            $html = $observer->getTransport()->getHtml();
            $observer->getTransport()->setHtml(
                $data['top'] . $html . '<br />' . $data['bottom']
            );
        }
    }

    protected function _getFieldsText($type, $checkoutType = 'onepage')
    {
        if($this->_isMultishipping)
        {
            $checkoutType = 'multishipping';
        }
        $helper = Mage::helper('aitcheckoutfields');
        $top = $helper->getCustomFieldTextValues($type, 1, $checkoutType);
        $bottom = $helper->getCustomFieldTextValues($type, 2, $checkoutType);
        if($top || $bottom) 
        {
            return array('top'=>$top, 'bottom'=>$bottom);
        }
        return false;
    }

}