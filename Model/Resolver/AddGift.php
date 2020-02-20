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
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Mageplaza\FreeGifts\Api\Data\AddGiftItemInterface;
use Mageplaza\FreeGifts\Api\ProductGiftInterface;
use Mageplaza\FreeGifts\Api\Data\AddGiftItemInterfaceFactory;
use Magento\Quote\Api\Data\ProductOptionInterfaceFactory;
use Magento\ConfigurableProduct\Api\Data\ConfigurableItemOptionValueInterfaceFactory;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;

/**
 * Class AddGift
 * @package Mageplaza\FreeGiftsGraphQl\Model\Resolver
 */
class AddGift implements ResolverInterface
{
    /**
     * @var ProductGiftInterface
     */
    protected $_productGift;
    
    /**
     * @var AddGiftItemInterfaceFactory
     */
    protected $_addGiftItem;
    
    /**
     * @var ProductOptionInterfaceFactory
     */
    protected $_productOption;
    
    /**
     * @var ConfigurableItemOptionValueInterfaceFactory
     */
    protected $_ConfigurableItemOption;
    
    /**
     * @var GetCartForUser
     */
    protected $_getCartForUser;
    
    /**
     * AddByGiftId constructor.
     * @param ProductGiftInterface $productGift
     * @param AddGiftItemInterfaceFactory $addGiftItem
     * @param ConfigurableItemOptionValueInterfaceFactory $configurableItemOption
     * @param ProductOptionInterfaceFactory $productOption
     * @param GetCartForUser $getCartForUser
     */
    public function __construct(
        ProductGiftInterface $productGift,
        AddGiftItemInterfaceFactory $addGiftItem,
        ConfigurableItemOptionValueInterfaceFactory $configurableItemOption,
        ProductOptionInterfaceFactory $productOption,
        GetCartForUser $getCartForUser
    ) {
        $this->_productGift = $productGift;
        $this->_addGiftItem = $addGiftItem;
        $this->_ConfigurableItemOption = $configurableItemOption;
        $this->_productOption = $productOption;
        $this->_getCartForUser = $getCartForUser;
    }
    
    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->validateArgs($args);
        $result = $this->_productGift->addGift($this->getAddGiftItem($args['input'], $context));
    
        return [
            'status' => $result->getStatus(),
            'message' => $result->getMessage(),
            'rule_id' => $result->getRuleId(),
            'quote_id' => $result->getQuoteId(),
            'quote_item_id' => $result->getQuoteItemId(),
            'product_gift_id' => $result->getProductGiftId()
        ];
    }
    
    /**
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    public function validateArgs($args)
    {
        if (!isset($args['input'])) {
            throw new GraphQlInputException(__('Required parameter "input" is missing'));
        }
        
        if (!isset($args['input']['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }
        
        if (!isset($args['input']['rule_id'])) {
            throw new GraphQlInputException(__('Required parameter "rule_id" is missing'));
        }
    
        if (!isset($args['input']['gift_id'])) {
            throw new GraphQlInputException(__('Required parameter "gift_id" is missing'));
        }
    }
    
    /**
     * @param array $data
     * @param ContextInterface $context
     * @return AddGiftItemInterface
     * @throws GraphQlInputException
     */
    public function getAddGiftItem($data, $context)
    {
        $configOptions = [];
        $giftItem = $this->_addGiftItem->create();
        $productOption = $this->_productOption->create();
        $productOptionExt = $productOption->getExtensionAttributes();
        
        try {
            $storeId = (int) $context->getExtensionAttributes()->getStore()->getId();
            $cart = $this->_getCartForUser->execute($data['cart_id'], $context->getUserId(), $storeId);
        } catch (\Exception $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
        
        $giftItem->setRuleId($data['rule_id'])->setQuoteId($cart->getId())->setGiftId($data['gift_id']);
        if (isset($data['configurable_options']) && $productOptionExt) {
            foreach ($data['configurable_options'] as $item) {
                $configOption = $this->_ConfigurableItemOption->create();
                $configOption->setOptionId($item['option_id']);
                $configOption->setOptionValue($item['option_value']);
                $configOptions[] = $configOption;
            }
        }
        
        if (!empty($configOptions)) {
            $giftOptionExt = $productOptionExt->setConfigurableItemOptions($configOptions);
            $giftItem->setProductOption($productOption->setExtensionAttributes($giftOptionExt));
        }
        
        return $giftItem;
    }
}
