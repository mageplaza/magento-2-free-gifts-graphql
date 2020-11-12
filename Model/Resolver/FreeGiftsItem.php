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

use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\FreeGifts\Api\Data\FreeGiftItemInterface;
use Mageplaza\FreeGifts\Plugin\QuoteApi\AbstractCartItem;
use Mageplaza\FreeGifts\Helper\Rule as HelperRule;

/**
 * Class FreeGiftsItem
 * @package Mageplaza\FreeGiftsGraphql\Model\Resolver
 */
class FreeGiftsItem extends AbstractCartItem implements ResolverInterface
{
    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
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
