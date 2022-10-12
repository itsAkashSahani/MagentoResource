<?php
namespace Ambab\EmiCalculator\Api;

interface BankRepositoryInterface
{
	public function save(\Ambab\EmiCalculator\Api\Data\BankInterface $bank);

    public function getById($bankId);

    public function delete(\Ambab\EmiCalculator\Api\Data\BankInterface $bank);

    public function deleteById($bankId);
}
?>
