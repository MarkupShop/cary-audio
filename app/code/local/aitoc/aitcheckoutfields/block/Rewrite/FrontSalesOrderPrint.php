<?php

/* AITOC static rewrite inserts start */
/* $meta=%default,AdjustWare_Deliverydate% */
if(Mage::helper('core')->isModuleEnabled('AdjustWare_Deliverydate')){
    class Aitoc_Aitcheckoutfields_Block_Rewrite_FrontSalesOrderPrint_Aittmp extends AdjustWare_Deliverydate_Block_Rewrite_SalesOrderPrint {} 
 }else{
    /* default extends start */
    class Aitoc_Aitcheckoutfields_Block_Rewrite_FrontSalesOrderPrint_Aittmp extends Mage_Sales_Block_Order_Print {}
    /* default extends end */
}

/* AITOC static rewrite inserts end */
class Aitoc_Aitcheckoutfields_Block_Rewrite_FrontSalesOrderPrint extends Aitoc_Aitcheckoutfields_Block_Rewrite_FrontSalesOrderPrint_Aittmp  {

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