const merge = require('webpack-merge');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const getConfiguration = require('./development');

const configurationProdMode = async appSettings => merge(await getConfiguration(appSettings, true), {
    webpack: {
        mode: 'production',
        devtool: false,

        optimization: {
            minimizer: [
                new UglifyJsPlugin({
                    cache: true,
                    parallel: true,
                    uglifyOptions: {
                        output: {
                            beautify: false
                        }
                    }
                }),

                new OptimizeCSSAssetsPlugin({
                    cssProcessorOptions: {
                        discardEmpty: true,
                        discardComments: {
                            removeAll: true
                        }
                    }
                })
            ]
        }
    }
});

module.exports = configurationProdMode;
