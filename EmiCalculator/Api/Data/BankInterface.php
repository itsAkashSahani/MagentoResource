<?php
namespace Ambab\EmiCalculator\Api\Data;

interface BankInterface {
    const BANK_ID = 'bank_id';
    const BANK_NAME = 'bank_name';
    const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

    public function getBankId();
    public function getBankName();
    public function getCreatedAt();
    public function getUpdatedAt();

    public function setBankId($id);
    public function setBankName($bankName);
    public function setCreatedAt($createdAt);
    public function setUpdatedAt($updatedAt);
}