import AssetsPlugin from 'assets-webpack-plugin';
import ExtractTextPlugin from 'extract-text-webpack-plugin';
import autoprefixer from 'autoprefixer';
import path from 'path';
import webpack from 'webpack';

const ASSET_PATH = process.env.ASSET_PATH || 'assets/';
const NODE_ENV = process.env.NODE_ENV || 'development';
const IS_PROD = NODE_ENV === 'production';

const CSS_BUNDLE_FILE = IS_PROD ? '[chunkhash].[name].css' : '[name].css';

const extractCss = new ExtractTextPlugin({
    filename: CSS_BUNDLE_FILE,
    allChunks: true
});

const plugins = [
    new webpack.optimize.CommonsChunkPlugin({
        names: ['vendor', 'manifest'],
        minChunks: Infinity
    }),
    new webpack.DefinePlugin({
        'process.env': {
            NODE_ENV: JSON.stringify(NODE_ENV),
            ASSET_PATH: JSON.stringify(ASSET_PATH)
        }
    }),
    new AssetsPlugin({filename: './app/Resources/assets/rev-manifest.json'})
];

plugins.push(extractCss);

if (IS_PROD) {
    plugins.push(
    new webpack.LoaderOptionsPlugin({
        minimize: true,
        debug: false
    }),
    new webpack.optimize.UglifyJsPlugin({
        compress: {
            warnings: false,
            screw_ie8: true,
            conditionals: true,
            unused: true,
            comparisons: true,
            sequences: true,
            dead_code: true,
            evaluate: true,
            if_return: true,
            join_vars: true
        },
        output: {comments: false}
    })
  );
}


const setup = {
    resolve: {
        modules: [
            path.resolve('assets'),
            'node_modules'
        ]
    },
    entry: {
        main: './src/AppBundle/Resources/public/js/index.js',
        vendor: ['react', 'react-dom']
    },
    output: {
        publicPath: ASSET_PATH,
        path: './web/assets/',
        filename: IS_PROD ? '[chunkhash].[name].js' : '[name].js'
    },
    module: {
        loaders: [
            {
                test: /\.scss$/,
                loader: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: [
                        'css-loader?sourceMap=true&localIdentName=[name]_[local]_[hash:base64:5]&importLoaders=1!postcss-loader',
                        'postcss-loader',
                        'sass-loader?outputStyle=expanded&sourceMap=true&sourceMapContents=true'
                    ]
                })
            },
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                loader: 'babel-loader'
            }
        ]
    },
    plugins
};

module.exports = setup;