<?php
namespace Akash\DatabaseSetup\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $date;
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->date = $date;
    }
     
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    { 
        $bankdata = [
            [
                'bank_name' => 'HDFC Bank',
                'bank_code' => 'HDFC',
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],  
            [
                'bank_name' => 'Axis Bank',
                'bank_code' => 'AXIS',
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_name' => 'Kotak Mahindra Bank',
                'bank_code' => 'KOTAK',
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_name' => 'ICICI Bank',
                'bank_code' => 'ICICI',
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_name' => 'State Bank of India',
                'bank_code' => 'SBI',
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_name' => 'Bank of Baroda',
                'bank_code' => 'BOB',
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_name' => 'YES Bank',
                'bank_code' => 'YESB',
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_name' => 'Union Bank',
                'bank_code' => 'UNB',
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ]
        ];
        
        foreach($bankdata as $data) {
            $setup->getConnection()->insert($setup->getTable('ambab_banks'), $data);
        }

        $emidata = [
            [
                'bank_code' => 'HDFC',
                'month' => 6,
                'interest' => 12,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'HDFC',
                'month' => 3,
                'interest' => 9,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'HDFC',
                'month' => 9,
                'interest' => 15,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'HDFC',
                'month' => 12,
                'interest' => 18,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'KOTAK',
                'month' => 6,
                'interest' => 10.5,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'KOTAK',
                'month' => 3,
                'interest' => 8,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],  
            [
                'bank_code' => 'ICICI',
                'month' => 3,
                'interest' => 10,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'ICICI',
                'month' => 6,
                'interest' => 10,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'ICICI',
                'month' => 9,
                'interest' => 15,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'ICICI',
                'month' => 12,
                'interest' => 15,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            
            [
                'bank_code' => 'AXIS',
                'month' => 3,
                'interest' => 12,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'AXIS',
                'month' => 6,
                'interest' => 12,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'AXIS',
                'month' => 9,
                'interest' => 15,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'AXIS',
                'month' => 12,
                'interest' => 15,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],

            [
                'bank_code' => 'BOB',
                'month' => 3,
                'interest' => 9.75,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'BOB',
                'month' => 6,
                'interest' => 9.75,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'BOB',
                'month' => 9,
                'interest' => 9.75,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ],
            [
                'bank_code' => 'BOB',
                'month' => 12,
                'interest' => 9.75,
                'status' => 1,
                'created_at' => $this->date->date(),
                'updated_at' => $this->date->date()
            ]
        ];

        foreach($emidata as $data) {
            $setup->getConnection()->insert($setup->getTable('ambab_emi_options'), $data);
        }
    }
}
?>