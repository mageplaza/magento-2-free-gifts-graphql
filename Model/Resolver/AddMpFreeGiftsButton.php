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

use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Mageplaza\FreeGifts\Plugin\QuoteApi\AbstractCart;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Api\Data\CartInterface;
use Mageplaza\FreeGifts\Api\Data\FreeGiftButtonInterface;

/**
 * Class AddMpFreeGiftsButton
 * @package Mageplaza\FreeGiftsGraphql\Model\Resolver
 */
class AddMpFreeGiftsButton extends AbstractCart implements ResolverInterface
{
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

        $extAttr    = $context->getExtensionAttributes();
        $buttonData = [];

        if ($extAttr !== null && $this->_helperRule->getHelperData()->isEnabled()) {
            $this->cartRule->getValidatedCartRules();
            $buttonData = [
                FreeGiftButtonInterface::IS_SHOW_BUTTON => (
                        $this->cartRule->hasManualCartRule() || $this->helperData->getCartPage()
                    ) && $this->cartRuleId(),
                FreeGiftButtonInterface::RULE_ID        => $this->cartRuleId(),
                FreeGiftButtonInterface::BUTTON_LABEL   => $this->helperData->getButtonLabel(),
                FreeGiftButtonInterface::BUTTON_COLOR   => $this->helperData->getButtonColor(),
                FreeGiftButtonInterface::TEXT_COLOR     => $this->helperData->getTextColor()
            ];
        }

        return $buttonData;
    }
}
