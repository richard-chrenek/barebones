const Encore = require('@symfony/webpack-encore');
const CompressionPlugin = require('compression-webpack-plugin');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build/')
    .setManifestKeyPrefix('build/')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(Encore.isDev())
    .addEntry('app', './assets/app.ts')
    .addEntry('error', './assets/error.ts')
    .enableStylusLoader()
    .enableVersioning(Encore.isProduction())
    .splitEntryChunks()
    .disableSingleRuntimeChunk()
    .enableTypeScriptLoader()
    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            config: 'postcss.config.js'
        };
    })
    .addPlugin(new CompressionPlugin({
        test: /\.(js|css)$/,
        minRatio: 0.8,
        algorithm: 'gzip',
        deleteOriginalAssets: false
    }))
    .addPlugin(new CompressionPlugin({
        algorithm: 'brotliCompress',
        test: /\.(js|css|html|svg)$/,
        compressionOptions: {
            level: 11,
        },
        threshold: 10240,
        minRatio: 0.8,
    }))
;

const config = Encore.getWebpackConfig();

module.exports = config;
