<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceCartConnector;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig as SprykerPriceCartConnectorConfig;

class PriceCartConnectorConfig extends SprykerPriceCartConnectorConfig
{
    /**
     * @var bool
     */
    protected const IS_ZERO_PRICE_ENABLED_FOR_CART_ACTIONS = false;

    /**
     * @return array<string>
     */
    public function getItemFieldsForIsSameItemComparison(): array
    {
        return array_merge(parent::getItemFieldsForIsSameItemComparison(), [
            ItemTransfer::MERCHANT_REFERENCE,
            ItemTransfer::PRODUCT_OFFER_REFERENCE,
        ]);
    }
}
