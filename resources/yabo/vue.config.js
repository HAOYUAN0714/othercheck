const path = require('path');
const webpack = require('webpack');

function resolve(dir) {
    return path.join(__dirname, '..', dir)
}
module.exports = {
    devServer: {
        disableHostCheck: true,
        port: 8881,
        host: '0.0.0.0',
        proxy: {
            '/exsport': {
                // target: 'https://wstt.66chile.com/exsport/', // 正式站
                target: 'https://wsws.66boxing.com/exsport/', // 測試站
                changeOrigin: true,
                pathRewrite: {
                    '^/exsport': ''
                }
            },
            '/api': {
                target: 'https://wstt.66chile.com/api/',
                changeOrigin: true,
                pathRewrite: {
                    '^/api': ''
                }
            }
        }
    },

    pages: {
        index: {
            entry: `./src/main/${process.env.VUE_APP_PROJECT_MAIN}.js`
        },
    },
    assetsDir: 'static',
    outputDir: `../../public/exsport/${process.env.VUE_APP_PROJECT_DIR}/`,
    indexPath: `../../../resources/views/${process.env.VUE_APP_PROJECT_DIR}.blade.php`,
    publicPath: process.env.NODE_ENV === 'production' ?
        `../../exsport/${process.env.VUE_APP_PROJECT_DIR}/` : `/`,
    productionSourceMap: false,
    configureWebpack: {
        resolve: {
            extensions: ['.js', '.vue', '.json'],
            modules: [
                resolve('src'),
                resolve('node_modules')
            ],
            alias: {
                'vue$': 'vue/dist/vue.common.js',
                'src': resolve('src'),
                'assets': resolve('src/assets'),
                'components': resolve('src/components'),
                'common': resolve('src/common'),
                'config': resolve('src/config')
            }
        }
    },
    chainWebpack: (config) => {
        const svgRule = config.module.rule('svg');

        svgRule.uses.clear();

        svgRule
            .use('babel-loader')
            .loader('babel-loader')
            .end()
            .use('vue-svg-loader')
            .loader('vue-svg-loader');
    },
};
