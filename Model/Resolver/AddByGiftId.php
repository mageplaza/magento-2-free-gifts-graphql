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

namespace Mageplaza\FreeGiftsGraphQL\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\FreeGifts\Api\ProductGiftInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Mageplaza\FreeGifts\Helper\Rule as HelperRule;

/**
 * Class AddByGiftId
 * @package Mageplaza\FreeGiftsGraphQL\Model\Resolver
 */
class AddByGiftId implements ResolverInterface
{
    /**
     * @var ProductGiftInterface
     */
    protected $_productGift;
    
    /**
     * DeleteByQuoteItem constructor.
     * @param ProductGiftInterface $productGift
     */
    public function __construct(
        ProductGiftInterface $productGift
    ) {
        $this->_productGift = $productGift;
    }
    
    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->validateArgs($args);
        $result = $this->_productGift->addGiftById($args['quoteId'], $args['ruleId'], $args['giftId']);
        if (is_array($result) && isset($result[0]['error'])) {
            throw new GraphQlInputException($result[0]['message']);
        }
        
        if (!$result instanceof QuoteItem) {
            throw new GraphQlInputException(__('Something went wrong.'));
        }
    
        return [
            'item_id' => $result->getItemId(),
            'sku' => $result->getSku(),
            'qty' => $result->getQty(),
            'name' => $result->getName(),
            'product_type' => $result->getProductType(),
            'quote_id' => $result->getQuoteId(),
            'rule_id' => $result->getDataByKey(HelperRule::QUOTE_RULE_ID),
        ];
    }
    
    /**
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    public function validateArgs($args)
    {
        if (!isset($args['quoteId'])) {
            throw new GraphQlInputException(__('Quote id is required.'));
        }
        
        if (!isset($args['ruleId'])) {
            throw new GraphQlInputException(__('Rule id is required.'));
        }
    
        if (!isset($args['giftId'])) {
            throw new GraphQlInputException(__('Gift id is required.'));
        }
    }
}
