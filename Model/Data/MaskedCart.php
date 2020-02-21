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

namespace Mageplaza\FreeGiftsGraphQl\Model\Data;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Quote\Model\Quote;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Mageplaza\FreeGifts\Helper\Data as HelperData;

/**
 * Class MaskedCart
 * @package Mageplaza\FreeGiftsGraphQl\Model\Data
 */
class MaskedCart
{
    /**
     * @var GetCartForUser
     */
    protected $_getCartForUser;
    
    /**
     * @var HelperData
     */
    protected $_helperData;
    
    /**
     * MaskedCart constructor.
     * @param GetCartForUser $getCartForUser
     * @param HelperData $helperData
     */
    public function __construct(
        GetCartForUser $getCartForUser,
        HelperData $helperData
    ) {
        $this->_getCartForUser = $getCartForUser;
        $this->_helperData = $helperData;
    }
    
    /**
     * @param string $maskedId
     * @param ContextInterface $context
     * @return Quote
     * @throws NoSuchEntityException
     * @throws GraphQlAuthorizationException
     * @throws GraphQlNoSuchEntityException
     */
    public function getCartByMaskedId($maskedId, $context)
    {
        if ($this->_helperData->versionCompare('2.3.2', '<=')) {
            return $this->_getCartForUser->execute($maskedId, $context->getUserId());
        }
        
        $storeId = (int) $context->getExtensionAttributes()->getStore()->getId();
        return $this->_getCartForUser->execute($maskedId, $context->getUserId(), $storeId);
    }
}
