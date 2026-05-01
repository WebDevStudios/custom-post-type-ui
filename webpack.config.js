const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,
	entry: {
		"cptui": './src/js/cptui.js',
		"cptui-editor": './src/js/cptui-editor.js',
	},
	optimization: {
		minimize: false
	},
	devtool: 'source-map',
};
