const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,
	entry: {
		cptui: './src/js/cptui.js',
		dashiconsPicker: './src/js/dashicons-picker'
	}
};
