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

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Quote\Model\Quote\ItemFactory as QuoteItemFactory;
use Mageplaza\FreeGifts\Api\ProductGiftInterface;

/**
 * Class AbstractGiftQuery
 * @package Mageplaza\FreeGiftsGraphQl\Model\Resolver
 */
abstract class AbstractGiftQuery
{
    /**
     * @var ProductGiftInterface
     */
    protected $_productGift;
    
    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;
    
    /**
     * @var QuoteItemFactory
     */
    protected $_quoteItemFactory;
    
    /**
     * @var int
     */
    protected $_productSkuFlag;
    
    /**
     * @var int
     */
    protected $_quoteItemFlag;
    
    /**
     * AbstractGift constructor.
     * @param ProductGiftInterface $productGift
     * @param QuoteItemFactory $quoteItemFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductGiftInterface $productGift,
        QuoteItemFactory $quoteItemFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->_productGift = $productGift;
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
        if ($this->_quoteItemFlag && !isset($args['item_id'])) {
            throw new GraphQlInputException(__('Quote item id is required.'));
        }
        if ($this->_quoteItemFlag) {
            $quoteItem = $this->_quoteItemFactory->create();
            $this->_quoteItemFlag = $quoteItem->load($args['item_id'])->getItemId();
            if (!$this->_quoteItemFlag) {
                throw new GraphQlInputException(__('This quote item does not exist.'));
            }
        }
        
        if ($this->_productSkuFlag && !isset($args['sku'])) {
            throw new GraphQlInputException(__('Product SKU is required.'));
        }
        if ($this->_productSkuFlag) {
            try {
                $this->_productSkuFlag = $this->_productRepository->get($args['sku'])->getSku();
            } catch (Exception $e) {
                throw new GraphQlInputException(__($e->getMessage()));
            }
        }
    }
}
