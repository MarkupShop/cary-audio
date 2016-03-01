<?php
/**
* @copyright  Copyright (c) 2011 AITOC, Inc. 
*/

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('catalog_eav_attribute')}
  ADD COLUMN `ait_in_excel` tinyint(1) NOT NULL  DEFAULT '0' after `is_display_in_invoice`;
");

$installer->endSetup();
