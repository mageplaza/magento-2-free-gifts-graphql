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

namespace Mageplaza\FreeGiftsGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\FreeGifts\Api\Data\FreeGiftItemInterface;
use Mageplaza\FreeGifts\Plugin\QuoteApi\AbstractCartItem;
use Mageplaza\FreeGifts\Helper\Rule as HelperRule;
use Mageplaza\FreeGifts\Helper\Data as HelperData;
use Magento\Quote\Model\Quote\ItemFactory;
use Magento\Quote\Model\QuoteFactory;
use Mageplaza\FreeGifts\Api\Data\FreeGiftItemInterfaceFactory;
use Mageplaza\FreeGifts\Api\ProductGiftFactory;
use Mageplaza\FreeGifts\Api\ProductGiftInterface;

/**
 * Class FreeGiftsItem
 * @package Mageplaza\FreeGiftsGraphQl\Model\Resolver
 */
class FreeGiftsItem extends AbstractCartItem implements ResolverInterface
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * FreeGiftsItem constructor.
     *
     * @param HelperRule $helperRule
     * @param ItemFactory $itemFactory
     * @param FreeGiftItemInterfaceFactory $freeGiftItem
     * @param ProductGiftInterface $productGift
     * @param QuoteFactory $quoteFactory
     * @param HelperData $helperData
     */
    public function __construct(
        HelperRule $helperRule,
        ItemFactory $itemFactory,
        FreeGiftItemInterfaceFactory $freeGiftItem,
        ProductGiftInterface $productGift,
        QuoteFactory $quoteFactory,
        HelperData $helperData
    ) {
        $this->helperData = $helperData;
        parent::__construct($helperRule, $itemFactory, $freeGiftItem, $productGift, $quoteFactory);
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isEnabled()) {
            throw new GraphQlNoSuchEntityException(__('Module is disabled.'));
        }

        if (!array_key_exists('model', $value) || !$value['model'] instanceof CartItemInterface) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $itemFactory = $this->_itemFactory->create();
        $quoteItem   = $itemFactory->load($value['model']->getItemId());

        if ($ruleId = (int)$quoteItem->getData(HelperRule::QUOTE_RULE_ID)) {
            $data = [
                FreeGiftItemInterface::IS_FREE_GIFT      => true,
                FreeGiftItemInterface::RULE_ID           => $ruleId,
                FreeGiftItemInterface::FREE_GIFT_MESSAGE => $this->_helperRule->getRuleById($ruleId)->getNoticeContent(),
                FreeGiftItemInterface::ALLOW_NOTICE      => $this->_helperRule->getRuleById($ruleId)->getAllowNotice()
            ];
        } else {
            $data = [
                FreeGiftItemInterface::IS_FREE_GIFT => false
            ];
        }

        return $data;
    }
}
