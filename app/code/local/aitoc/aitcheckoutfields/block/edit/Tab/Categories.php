<?php

class Aitoc_Aitcheckoutfields_Block_Edit_Tab_Categories extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
    public function getCategoryIds() {
        return Mage::getModel('aitcheckoutfields/attributecatalogrefs')->getRefs(Mage::app()->getRequest()->getParam('attribute_id'),'category');
    }
}

?>
