<?php

class Aitoc_Aitcheckoutfields_Block_Rewrite_AdminSalesOrderCreateFormAccount  extends Mage_Adminhtml_Block_Sales_Order_Create_Form_Account
{
    protected function _toHtml()
    {
    	$html = parent::_toHtml();
    	$fBlock = $this->getLayout()->createBlock('aitcheckoutfields/ordercreate_form')->toHtml();
    	return $html.$fBlock;
    }
}