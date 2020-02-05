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

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Quote\Model\Quote\ItemFactory as QuoteItemFactory;
use Mageplaza\FreeGifts\Api\ProductGiftInterface;
use Mageplaza\FreeGifts\Helper\Rule as HelperRule;

/**
 * Class AbstractGift
 * @package Mageplaza\FreeGiftsGraphQL\Model\Resolver
 */
abstract class AbstractGift
{
    /**
     * @var ProductGiftInterface
     */
    protected $_productGift;
    
    /**
     * @var HelperRule
     */
    protected $_helperRule;
    
    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;
    
    /**
     * @var string
     */
    protected $_productSku;
    
    /**
     * @var int
     */
    protected $_quoteItem;
    
    /**
     * @var QuoteItemFactory
     */
    protected $_quoteItemFactory;
    
    /**
     * AbstractGift constructor.
     * @param ProductGiftInterface $productGift
     * @param HelperRule $helperRule
     * @param QuoteItemFactory $quoteItemFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductGiftInterface $productGift,
        HelperRule $helperRule,
        QuoteItemFactory $quoteItemFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->_productGift = $productGift;
        $this->_helperRule = $helperRule;
        $this->_quoteItemFactory = $quoteItemFactory;
        $this->_productRepository = $productRepository;
    }
    
    /**
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    protected function validateArgs(array $args)
    {
        if ($this->_quoteItem && !isset($args['itemId'])) {
            throw new GraphQlInputException(__('Quote item id is required.'));
        }
        if ($this->_quoteItem) {
            $quoteItem = $this->_quoteItemFactory->create();
            $this->_quoteItem = $quoteItem->load($args['itemId'])->getItemId();
            if (!$this->_quoteItem) {
                throw new GraphQlInputException(__('This quote item does not exist.'));
            }
        }
        
        if ($this->_productSku && !isset($args['sku'])) {
            throw new GraphQlInputException(__('Product SKU is required.'));
        }
        if ($this->_productSku) {
            try {
                $this->_productSku = $this->_productRepository->get($args['sku'])->getSku();
            } catch (Exception $e) {
                throw new GraphQlInputException(__($e->getMessage()));
            }
        }
    }
}
