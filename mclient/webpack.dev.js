const common = require('./webpack.common.js');
const webpack = require('webpack');
const merge = require('webpack-merge');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = merge(common, {
	devtool: 'inline-source-map',
	plugins: [
		new BundleAnalyzerPlugin(),
		new webpack.DefinePlugin({"API_URL": JSON.stringify("https://0.0.0.0:8000/api")})
	],
	devServer: {
		historyApiFallback: true,
		noInfo: true,
		https: true,
		host: "0.0.0.0",
		disableHostCheck: true
	}
});