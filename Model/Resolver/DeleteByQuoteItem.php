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

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\FreeGiftsGraphQl\Model\Data\MaskedCart;
use Mageplaza\FreeGifts\Api\ProductGiftInterface;

/**
 * Class DeleteByQuoteItem
 * @package Mageplaza\FreeGiftsGraphQl\Model\Resolver
 */
class DeleteByQuoteItem implements ResolverInterface
{
    /**
     * @var ProductGiftInterface
     */
    protected $_productGift;
    
    /**
     * @var MaskedCart
     */
    protected $_maskedCart;
    
    /**
     * DeleteByQuoteItem constructor.
     * @param ProductGiftInterface $productGift
     * @param MaskedCart $maskedCart
     */
    public function __construct(
        ProductGiftInterface $productGift,
        MaskedCart $maskedCart
    ) {
        $this->_productGift = $productGift;
        $this->_maskedCart = $maskedCart;
    }
    
    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->validateArgs($args);
        $cart = $this->_maskedCart->getCartByMaskedId((string) $args['cart_id'], $context->getUserId());
        $result = $this->_productGift->deleteGiftByQuoteItemId($cart->getId(), $args['item_id']);
        
        if (is_object($result) && $result->getStatus() === 'error') {
            throw new GraphQlInputException($result->getMessage());
        }
        
        return true;
    }
    
    /**
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    public function validateArgs($args)
    {
        if (!isset($args['item_id'])) {
            throw new GraphQlInputException(__('Required parameter "item_id" is missing'));
        }
    
        if (!isset($args['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }
    }
}
