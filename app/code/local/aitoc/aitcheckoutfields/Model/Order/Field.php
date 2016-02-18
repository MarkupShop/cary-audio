<?php
class Aitoc_Aitcheckoutfields_Model_Order_Field extends Aitoc_Aitcheckoutfields_Model_Field_Abstract
{
    protected $_eventPrefix = 'aitcfm_order_field';
    
    protected $_fieldType = 'order';

    protected function _construct()
    {
        $this->_init('aitcheckoutfields/order_field');
    }
}
