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

use Mageplaza\FreeGifts\Api\ProductGiftInterface;
use Mageplaza\FreeGifts\Helper\Rule as HelperRule;

/**
 * Class AbstractGiftMutation
 * @package Mageplaza\FreeGiftsGraphQL\Model\Resolver
 */
abstract class AbstractGiftMutation
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
     * AbstractGiftMutation constructor.
     * @param ProductGiftInterface $productGift
     * @param HelperRule $helperRule
     */
    public function __construct(
        ProductGiftInterface $productGift,
        HelperRule $helperRule
    ) {
        $this->_productGift = $productGift;
        $this->_helperRule = $helperRule;
    }
}
