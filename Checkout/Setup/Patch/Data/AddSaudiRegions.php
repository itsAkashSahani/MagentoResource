<?php
declare(strict_types=1);

namespace Albatool\Checkout\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddSaudiRegions implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /**
         * Fill table directory/country_region
         * Fill table directory/country_region_name for el_GR locale
         * Fill table directory/country_region_name for en_US locale
         */
        $data = [
            ['SA', 'MR', 'مِنْطَقَة مَكَّة‎', 'Mecca Region'],
            ['SA', 'RR', 'منطقة الرياض‎', 'Riyadh Region'],
            ['SA', 'ER', 'المنطقة الشرقية', 'Eastern Region'],
            ['SA', 'AR', 'عَسِيرٌ‎', 'Asir Region'],
            ['SA', 'JR', 'جيزان‎', 'Jizan Region'],
            ['SA', 'MR', 'مِنْطَقَة ٱلْمَدِيْنَة ٱلْمُنَوَّرَة', 'Medina Region'],
            ['SA', 'QR', 'منطقة القصيم‎', 'Qassim Region'],
            ['SA', 'TR', 'مِنْطَقَة تَبُوْك', 'Tabuk Region'],
            ['SA', 'HR', 'مِنْطَقَة حَائِل', 'Ḥaʼil Region'],
            ['SA', 'NR', 'نجران‎', 'Najran Region'],
            ['SA', 'AJR', '‫منطقة الجوف‬', 'Al-Jawf Region'],
            ['SA', 'ABR', 'ٱلْبَاحَة‎', 'Al-Bahah Region'],
            ['SA', 'NBR', 'منطقة الحدود الشمالية', 'Northern Borders Region'],
        ];

        foreach ($data as $row) {
            $bind = ['country_id' => $row[0], 'code' => $row[1], 'default_name' => $row[3]];
            $this->moduleDataSetup->getConnection()->insert(
                $this->moduleDataSetup->getTable('directory_country_region'),
                $bind
            );
            $regionId = $this->moduleDataSetup->getConnection()->lastInsertId(
                $this->moduleDataSetup->getTable('directory_country_region')
            );

            $bind = ['locale' => 'en_US', 'region_id' => $regionId, 'name' => $row[3]];
            $this->moduleDataSetup->getConnection()->insert(
                $this->moduleDataSetup->getTable('directory_country_region_name'),
                $bind
            );

            $bind = ['locale' => 'el_SA', 'region_id' => $regionId, 'name' => $row[2]];
            $this->moduleDataSetup->getConnection()->insert(
                $this->moduleDataSetup->getTable('directory_country_region_name'),
                $bind
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
