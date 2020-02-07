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
        $result = $this->_productGift->deleteGiftByQuoteItemId($args['quoteId'], $args['itemId']);
        $result = is_array($result) ? reset($result): $result;
        if (isset($result['error'])) {
            throw new GraphQlInputException($result['message']);
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
        if (!isset($args['itemId'])) {
            throw new GraphQlInputException(__('Quote item id is required.'));
        }
    
        if (!isset($args['quoteId'])) {
            throw new GraphQlInputException(__('Quote id is required.'));
        }
    }
}
