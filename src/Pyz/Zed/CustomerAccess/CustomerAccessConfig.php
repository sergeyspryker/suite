<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerAccess;

use Spryker\Shared\CustomerAccess\CustomerAccessConfig as SprykerSharedCustomerAccessConfig;
use Spryker\Zed\CustomerAccess\CustomerAccessConfig as SprykerCustomerAccessConfig;

class CustomerAccessConfig extends SprykerCustomerAccessConfig
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getContentTypes(): array
    {
        return [
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_PRICE,
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_ORDER_PLACE_SUBMIT,
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_ADD_TO_CART,
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_WISHLIST,
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_SHOPPING_LIST,
        ];
    }

    /**
     * Gets default content type access for install (shopping list content type will be created with restricted access).
     *
     * @return array
     */
    public function getDefaultContentTypeAccess(): array
    {
        return [
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_PRICE => false,
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_ORDER_PLACE_SUBMIT => false,
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_ADD_TO_CART => false,
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_WISHLIST => false,
            SprykerSharedCustomerAccessConfig::CONTENT_TYPE_SHOPPING_LIST => true,
        ];
    }
}
