<?php

class Aitoc_Aitcheckoutfields_Model_Mysql4_Attributecategoryrefs extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('aitoc_custom_attribute_cat_refs', 'id');
    }    
}