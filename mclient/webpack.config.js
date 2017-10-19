const path = require("path");
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const HtmlWebpackPlugin = require('html-webpack-plugin');
const webpack = require('webpack');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

const htmlwebpack = new HtmlWebpackPlugin({
	title: "M Berlin",
	template: 'src/index.ejs'
});

module.exports = {
	entry: "./src/app.js",
	output: {
		path: path.join(__dirname, 'dist'),
		filename: "bundle.js"
	},
	module: {
		rules: [
			{ test: /\.svg$/, loader: 'svg-inline-loader' },
			{ test: /\.vue$/, loader: 'vue-loader', options: { loaders: {scss: 'style!css!sass'} } },
			{ test: /\.s[a|c]ss$/, loader: 'style!css!sass' },
			{ test: /\.(png|jpg|gif)$/, use: [ { loader: 'file-loader', options: {} } ] }
		],
		loaders: [ { test: /\.js$/, loader: 'babel', exclude: [/node_modules/, /build/, /__test__/], query: { presets: ['es2015'] } } ]
	},
	plugins: [
		htmlwebpack,
		new BundleAnalyzerPlugin(),
		new webpack.ContextReplacementPlugin(
			/moment[\/\\]locale$/,
			/en-gb|de/
		),
		new webpack.ProvidePlugin({
			$: "jquery",
			jQuery: "jquery",
			"window.jQuery": "jquery"
		})
	],
	stats: {
		colors: true
	},
	resolve: {
		alias: {
		  'vue$': 'vue/dist/vue.esm.js'
		}
	},
	devServer: {
		historyApiFallback: true,
		noInfo: true,
		host: "0.0.0.0",
		disableHostCheck: true
	},
	performance: {
		hints: false
	},
	devtool: 'source-map'
};

if (process.env.NODE_ENV === 'production') {
	module.exports.devtool = '#source-map'
	module.exports.plugins = (module.exports.plugins || []).concat([
		new webpack.DefinePlugin({
			'process.env': {
				NODE_ENV: '"production"'
			}
		}),
		new webpack.optimize.UglifyJsPlugin({
			sourceMap: true,
			compress: {
				warnings: false
			}
		}),
		new webpack.LoaderOptionsPlugin({
			minimize: true
		})
	])
}