<?php


namespace Mageplaza\FreeGiftsGraphQL\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class GiftByQuoteItem
 * @package Mageplaza\FreeGiftsGraphQL\Model\Resolver
 */
class GiftQueryByQuoteItem extends AbstractGiftQuery implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->_quoteItem = 1;
        $this->validateArgs($args);
        
        return $this->_productGift->getGiftsByQuoteItemId($this->_quoteItem);
    }
}
