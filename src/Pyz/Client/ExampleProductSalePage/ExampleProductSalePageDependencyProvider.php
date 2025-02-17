<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ExampleProductSalePage;

use Pyz\Client\ExampleProductSalePage\Plugin\Elasticsearch\Query\SaleSearchQueryPlugin;
use Spryker\Client\Catalog\Plugin\Elasticsearch\ResultFormatter\RawCatalogSearchResultFormatterPlugin;
use Spryker\Client\CatalogPriceProductConnector\Plugin\CurrencyAwareCatalogSearchResultFormatterPlugin;
use Spryker\Client\CatalogPriceProductConnector\Plugin\ProductPriceQueryExpanderPlugin;
use Spryker\Client\CustomerCatalog\Plugin\Search\ProductListQueryExpanderPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Search\SearchClient;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\FacetQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\LocalizedQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\PaginatedQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\SortedQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\StoreQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\PaginatedResultFormatterPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\SortedResultFormatterPlugin;

class ExampleProductSalePageDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_LABEL_STORAGE = 'CLIENT_PRODUCT_LABEL';

    /**
     * @var string
     */
    public const SALE_SEARCH_QUERY_PLUGIN = 'SALE_SEARCH_QUERY_PLUGIN';

    /**
     * @var string
     */
    public const SALE_SEARCH_QUERY_EXPANDER_PLUGINS = 'SALE_SEARCH_QUERY_EXPANDER_PLUGINS';

    /**
     * @var string
     */
    public const SALE_SEARCH_RESULT_FORMATTER_PLUGINS = 'SALE_SEARCH_RESULT_FORMATTER_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addSearchClient($container);
        $container = $this->addProductLabelClient($container);
        $container = $this->addSaleSearchQueryPlugin($container);
        $container = $this->addSaleSearchQueryExpanderPlugins($container);
        $container = $this->addSaleSearchResultFormatterPlugins($container);
        $container = $this->addStoreClient($container);
        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, function () {
            return new SearchClient();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductLabelClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_LABEL_STORAGE, function (Container $container) {
            return $container->getLocator()->productLabelStorage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSaleSearchQueryPlugin(Container $container): Container
    {
        $container->set(static::SALE_SEARCH_QUERY_PLUGIN, function () {
            return new SaleSearchQueryPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSaleSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::SALE_SEARCH_QUERY_EXPANDER_PLUGINS, function () {
            return [
                new StoreQueryExpanderPlugin(),
                new LocalizedQueryExpanderPlugin(),
                new ProductPriceQueryExpanderPlugin(),
                new SortedQueryExpanderPlugin(),
                new PaginatedQueryExpanderPlugin(),
                new ProductListQueryExpanderPlugin(),

                /*
                 * FacetQueryExpanderPlugin needs to be after other query expanders which filters down the results.
                 */
                new FacetQueryExpanderPlugin(),
            ];
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSaleSearchResultFormatterPlugins(Container $container): Container
    {
        $container->set(static::SALE_SEARCH_RESULT_FORMATTER_PLUGINS, function () {
            /** @phpstan-var \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface $rawCatalogSearchResultFormatterPlugin */
            $rawCatalogSearchResultFormatterPlugin = new RawCatalogSearchResultFormatterPlugin();

            return [
                new FacetResultFormatterPlugin(),
                new SortedResultFormatterPlugin(),
                new PaginatedResultFormatterPlugin(),
                new CurrencyAwareCatalogSearchResultFormatterPlugin(
                    $rawCatalogSearchResultFormatterPlugin,
                ),
            ];
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return $container->getLocator()->locale()->client();
        });

        return $container;
    }
}
