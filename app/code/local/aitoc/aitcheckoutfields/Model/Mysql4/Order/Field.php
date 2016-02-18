<?php
class Aitoc_Aitcheckoutfields_Model_Mysql4_Order_Field extends Aitoc_Aitcheckoutfields_Model_Mysql4_Field_Abstract
{
    protected function _construct()
    {
        $this->_init('aitcheckoutfields/order_field', 'value_id');
    }
}
