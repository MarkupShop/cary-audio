<?php
/**
* @copyright  Copyright (c) 2011 AITOC, Inc. 
*/

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE `aitoc_custom_attribute_cg` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST
");

$installer->endSetup();
