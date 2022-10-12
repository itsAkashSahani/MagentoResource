<?php
namespace Albatool\HairQuizConsultant\Setup;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var UpgradeSchemaInterface[]
     */
    private $pool;

    public function __construct(
        UpgradeSchema\UpgradeSchema101 $upgrade101,
        UpgradeSchema\UpgradeSchema102 $upgrade102,
        UpgradeSchema\UpgradeSchema103 $upgrade103,
        UpgradeSchema\UpgradeSchema104 $upgrade104,
        UpgradeSchema\UpgradeSchema105 $upgrade105,
        UpgradeSchema\UpgradeSchema106 $upgrade106,
        UpgradeSchema\UpgradeSchema107 $upgrade107
    ) {
        $this->pool = [
            '1.0.1' => $upgrade101,
            '1.0.2' => $upgrade102,
            '1.0.3' => $upgrade103,
            '1.0.4' => $upgrade104,
            '1.0.5' => $upgrade105,
            '1.0.6' => $upgrade106,
            '1.0.7' => $upgrade107
        ];
    }

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        foreach ($this->pool as $version => $upgrade) {
            if (version_compare($context->getVersion(), $version) < 0) {
                $upgrade->upgrade($setup, $context);
            }
        }

        $setup->endSetup();
    }
}