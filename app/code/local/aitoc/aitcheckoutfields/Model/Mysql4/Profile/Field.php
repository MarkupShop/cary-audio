<?php
class Aitoc_Aitcheckoutfields_Model_Mysql4_Profile_Field extends Aitoc_Aitcheckoutfields_Model_Mysql4_Field_Abstract
{
    protected function _construct()
    {
        $this->_init('aitcheckoutfields/profile_field', 'value_id');
    }
}
