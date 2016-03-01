<?php
/**
* @copyright  Copyright (c) 2011 AITOC, Inc. 
*/

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('catalog_eav_attribute')}
  ADD COLUMN `ait_product_category_dependant` tinyint(1) NOT NULL  DEFAULT '0' after `ait_in_excel`;
");

$installer->run("-- DROP TABLE IF EXISTS `aitoc_custom_attribute_cat_refs`;
CREATE TABLE IF NOT EXISTS `aitoc_custom_attribute_cat_refs` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`attribute_id` INT NOT NULL ,
`type` VARCHAR( 80 ) NOT NULL ,
`value` INT NOT NULL,
  KEY `attribute_id` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
