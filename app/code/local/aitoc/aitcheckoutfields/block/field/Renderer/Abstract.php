<?php

class Aitoc_Aitcheckoutfields_Block_Field_Renderer_Abstract  extends Mage_Core_Block_Abstract
{
    public function setParams(array $data) {
        foreach($data as $key => $value)
        {
            $this->$key=$value;
        }
        return $this;
    }
    
    public function render()
    {
        return '';
    }
}

?>
