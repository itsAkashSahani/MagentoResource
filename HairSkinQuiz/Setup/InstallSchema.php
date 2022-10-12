<?php

namespace Albatool\HairSkinQuiz\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0){

		$installer->run('create table hair_skin_quiz(id int not null auto_increment, 
			    hair_skin_quiz_name varchar(100),
			    hair_skin_quiz_email varchar(100),
			    hair_skin_quiz_gender varchar(10),
			    hair_skin_quiz_dob DATE,
			    hair_skin_quiz_medical_cond varchar(10),
			    hair_skin_quiz_special_offers varchar(10),
			    hair_skin_quiz_quetion_1 varchar(100),
			    hair_skin_quiz_quetion_2 varchar(100),
			    hair_skin_quiz_quetion_3 varchar(100),
			    hair_skin_quiz_quetion_4 varchar(100),
			    primary key(id))');


		//demo
//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//$scopeConfig = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
//$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/updaterates.log');
//$logger = new \Zend\Log\Logger();
//$logger->addWriter($writer);
//$logger->info('updaterates');
//demo 

		}

        $installer->endSetup();

    }
}