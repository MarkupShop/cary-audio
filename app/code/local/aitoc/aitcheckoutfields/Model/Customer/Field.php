<?php
class Aitoc_Aitcheckoutfields_Model_Customer_Field extends Aitoc_Aitcheckoutfields_Model_Field_Abstract
{
    protected $_eventPrefix = 'aitcfm_customer_field';
    
    protected $_fieldType = 'customer';

    protected function _construct()
    {
        $this->_init('aitcheckoutfields/customer_field');
    }
}
