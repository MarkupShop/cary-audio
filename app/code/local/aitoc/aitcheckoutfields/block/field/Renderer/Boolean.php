<?php

class Aitoc_Aitcheckoutfields_Block_Field_Renderer_Boolean extends Aitoc_Aitcheckoutfields_Block_Field_Renderer_Abstract 
{
    public function render() 
    {
                $select = Mage::getModel('core/layout')->createBlock('core/html_select')
                    ->setName($this->sFieldName)
                    ->setId($this->sFieldId)
                    ->setTitle($this->sLabel) 
                    ->setClass('validate-select')
                    ->setValue($this->sFieldValue)
                    ->setOptions(Mage::getModel('adminhtml/system_config_source_yesno')-> toOptionArray());
                    return $select->getHtml();
    }
}

?>
