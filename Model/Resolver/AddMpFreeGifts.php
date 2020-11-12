<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_FreeGiftsGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

declare(strict_types=1);

namespace Mageplaza\FreeGiftsGraphql\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Api\Data\CartInterface;
use Mageplaza\FreeGifts\Helper\Rule as HelperRule;
use Mageplaza\FreeGifts\Block\Cart\CartRule;
use Mageplaza\FreeGifts\Helper\Data as HelperData;
use Mageplaza\FreeGifts\Api\Data\FreeGiftButtonInterfaceFactory;

/**
 * Class AddMpFreeGifts
 * @package Mageplaza\FreeGiftsGraphql\Model\Resolver
 */
class AddMpFreeGifts implements ResolverInterface
{
    /**
     * @var HelperRule
     */
    protected $helperRule;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var CartRule
     */
    protected $cartRule;

    /**
     * @var FreeGiftButtonInterfaceFactory
     */
    protected $freeGiftButton;

    /**
     * AddMpFreeGifts constructor.
     *
     * @param HelperRule $helperRule
     * @param HelperData $helperData
     * @param CartRule $cartRule
     * @param FreeGiftButtonInterfaceFactory $freeGiftButton
     */
    public function __construct(
        HelperRule $helperRule,
        HelperData $helperData,
        CartRule $cartRule,
        FreeGiftButtonInterfaceFactory $freeGiftButton
    ) {
        $this->helperRule     = $helperRule;
        $this->helperData     = $helperData;
        $this->cartRule       = $cartRule;
        $this->freeGiftButton = $freeGiftButton;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isEnabled()) {
            throw new GraphQlNoSuchEntityException(__('Module is disabled.'));
        }

        if (!array_key_exists('model', $value) || !$value['model'] instanceof CartInterface) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $rules   = null;
        $extAttr = $context->getExtensionAttributes();

        if ($extAttr !== null && $this->helperRule->getHelperData()->isEnabled()) {
            $rules = $this->helperRule->setExtraData(false)->setQuote($value['model'])->getAllValidRules();
        }

        return $rules;
    }
}
