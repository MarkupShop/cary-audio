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
class Aitoc_Aitcheckoutfields_Block_Field_Renderer_Text extends Aitoc_Aitcheckoutfields_Block_Field_Renderer_Abstract 
{
    public function render() 
    {           
        $aParams=$this->getData('a_params');
        return '<input type="text" id="'.(isset($aParams['id'])?$aParams['id'].' ':'').'" class="'.(isset($aParams['class'])?$aParams['class'].' ':'').'input-text" name="'.$this->sFieldName.'" value="'.$this->sFieldValue.'">';
    }
}

?>
