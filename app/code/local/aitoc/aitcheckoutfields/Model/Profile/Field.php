<?php
class Aitoc_Aitcheckoutfields_Model_Profile_Field extends Aitoc_Aitcheckoutfields_Model_Field_Abstract
{
    protected $_eventPrefix = 'aitcfm_profile_field';
    
    protected $_fieldType = 'profile';

    protected function _construct()
    {
        $this->_init('aitcheckoutfields/profile_field');
    }
}
