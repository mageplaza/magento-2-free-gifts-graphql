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
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\FreeGifts\Api\ProductGiftInterface;
use Mageplaza\FreeGifts\Helper\Data as HelperData;

/**
 * Class Configs
 * @package Mageplaza\FreeGiftsGraphQl\Model\Resolver
 */
class Configs implements ResolverInterface
{
    /**
     * @var ProductGiftInterface
     */
    protected $_productGift;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * Configs constructor.
     *
     * @param ProductGiftInterface $productGift
     * @param HelperData $helperData
     */
    public function __construct(
        ProductGiftInterface $productGift,
        HelperData $helperData
    ) {
        $this->_productGift = $productGift;
        $this->helperData   = $helperData;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isEnabled()) {
            throw new GraphQlNoSuchEntityException(__('Module is disabled.'));
        }

        return $this->_productGift->getConfig();
    }
}
