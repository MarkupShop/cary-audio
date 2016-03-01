<?php

class Aitoc_Aitcheckoutfields_Block_Rewrite_FrontSalesOrderView  extends Mage_Sales_Block_Order_View
{
	public function _construct()
    {
    	parent::_construct();
        $this->setTemplate('aitcommonfiles/design--frontend--base--default--template--sales--order--view.phtml');
    }
        
    public function getOrderCustomData()
    {
        $iStoreId = $this->getOrder()->getStoreId();

        $oFront = Mage::app()->getFrontController();
        
        $iOrderId = $oFront->getRequest()->getParam('order_id');
        
        $oAitcheckoutfields  = Mage::getModel('aitcheckoutfields/aitcheckoutfields');

        $aCustomAtrrList = $oAitcheckoutfields->getOrderCustomData($iOrderId, $iStoreId, false, true);

        return $aCustomAtrrList;
    }
}
?>