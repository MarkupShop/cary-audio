<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Date
 *
 * @author kirichenko
 */
class Aitoc_Aitcheckoutfields_Block_Field_Renderer_Multiselect extends Aitoc_Aitcheckoutfields_Block_Field_Renderer_Abstract 
{
    public function render() 
    {
        $values = explode(',', $this->sFieldValue[0]);

        $select = Mage::getModel('core/layout')->createBlock('core/html_select')
                    ->setName($this->sFieldName . '[]')
                    ->setId($this->sFieldId)
                    ->setTitle($this->sLabel)
                    ->setClass($this->sFieldClass)
                    ->setValue($values)
                    ->setExtraParams('multiple')
                    ->setOptions($this->aOptionHash);
                
                    $sHidden = '<input type="hidden" name="'.$this->sFieldName.'"  value="" />';
                    
                    return $sHidden . $select->getHtml();
    }
}

?>
