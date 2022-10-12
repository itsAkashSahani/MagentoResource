<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Albatool\CronOrder\Model\Rewrite\SourceItem\Validator;

use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Magento\InventoryApi\Model\SourceItemValidatorInterface;
use Magento\Catalog\Model\Product;

/**
 * Responsible for Source items validation
 */
class SourceItemsValidator extends \Magento\Inventory\Model\SourceItem\Validator\SourceItemsValidator
{
    /**
     * @var SourceItemValidatorInterface
     */
    private $sourceItemValidator;

    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;
    protected $product;

    /**
     * @param ValidationResultFactory $validationResultFactory
     * @param SourceItemValidatorInterface $sourceItemValidator
     */
    public function __construct(
        ValidationResultFactory $validationResultFactory,
        SourceItemValidatorInterface $sourceItemValidator,
        Product $product
    ) {
        $this->validationResultFactory = $validationResultFactory;
        $this->sourceItemValidator = $sourceItemValidator;
        $this->product = $product;
    }

    /**
     * @param SourceItemInterface[] $sourceItems
     * @return ValidationResult
     */
    public function validate(array $sourceItems): ValidationResult
    {
        $errors = [[]];
        foreach ($sourceItems as $sourceItem) {
            $validationResult = $this->sourceItemValidator->validate($sourceItem);
            if ($this->product->getIdBySku($sourceItem->getSku())){
                if (!$validationResult->isValid()) {
                    $errors[] = $validationResult->getErrors();
                }
            }
            else{
                //echo "ITEM NOT FOUND <pre>";print_r($sourceItem->getSku());exit;
                $errors_text = $sourceItem->getSku()." sku not found";
                echo $errors_text;exit;
            }
        }
        $errors = array_merge(...$errors);

        $validationResult = $this->validationResultFactory->create(['errors' => $errors]);
        return $validationResult;
    }
}
