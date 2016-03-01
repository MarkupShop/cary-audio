<?php
/**
* @copyright  Copyright (c) 2010 AITOC, Inc. 
*/

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('catalog_eav_attribute')}
  ADD COLUMN `ait_filterable` tinyint(1) NOT NULL  DEFAULT '0' after `ait_registration_place`;
");

$installer->endSetup();
