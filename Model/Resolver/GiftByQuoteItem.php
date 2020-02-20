<?php


namespace Mageplaza\FreeGiftsGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class GiftByQuoteItem
 * @package Mageplaza\FreeGiftsGraphQl\Model\Resolver
 */
class GiftByQuoteItem extends AbstractGiftQuery implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->_quoteItemFlag = 1;
        $this->validateArgs($args);
        
        return $this->_productGift->getGiftsByQuoteItemId($this->_quoteItemFlag);
    }
}
