const common = require('./webpack.common.js');
const webpack = require('webpack');
const merge = require('webpack-merge');
const MinifyPlugin = require("babel-minify-webpack-plugin");
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = merge(common, {
  plugins: [
	new webpack.DefinePlugin({'process.env': {NODE_ENV: '"production"'}}),
	new webpack.DefinePlugin({"API_URL": JSON.stringify("https://m.jwels.berlin/")}),
    new MinifyPlugin({}, { test: /\.js$/, loader: 'babel', exclude: [/node_modules/, /build/, /__test__/], query: { presets: ['minify'] } }),
    new BundleAnalyzerPlugin()
  ]
});