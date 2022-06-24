<?php
/**
 * Custom Post Type UI WP-CLI.
 *
 * @package    CPTUI
 * @subpackage WP-CLI
 * @author     WebDevStudios
 * @since      1.6.0
 * @license    GPL-2.0+
 */

// phpcs:disable WebDevStudios.All.RequireAuthor

/**
 * Imports and exports Custom Post Type UI setting data.
 */
class CPTUI_Import_JSON extends WP_CLI_Command {

	public $args;

	public $assoc_args;

	public $type;

	public $data = [];

	/**
	 * Imports and parses JSON into CPTUI settings.
	 *
	 * ## Options
	 *
	 * [--type=<type>]
	 * : What type of import this is. Available options are `post_type` and `taxonomy`.
	 *
	 * [--data-path=<path>]
	 * : The server path to the file holding JSON data to import. Relative to PWD.
	 */
	public function import( $args, $assoc_args ) {
		$this->args       = $args;
		$this->assoc_args = $assoc_args;

		if ( ! isset( $this->assoc_args['type'] ) ) {
			WP_CLI::error( __( 'Please provide whether you are importing post types or taxonomies', 'custom-post-type-ui' ) );
		}

		if ( ! isset( $this->assoc_args['data-path'] ) ) {
			WP_CLI::error( __( 'Please provide a path to the file holding your CPTUI JSON data.', 'custom-post-type-ui' ) );
		}

		$this->type = $assoc_args['type'];

		$json = file_get_contents( $this->assoc_args['data-path'] );

		if ( empty( $json ) ) {
			WP_CLI::error( __( 'No JSON data found', 'custom-post-type-ui' ) );
		}

		if ( 'post_type' === $this->type ) {
			$this->data['cptui_post_import'] = json_decode( stripslashes_deep( trim( $json ) ), true );
		}

		if ( 'taxonomy' === $this->type ) {
			$this->data['cptui_tax_import'] = json_decode( stripslashes_deep( trim( $json ) ), true );
		}

		$result = cptui_import_types_taxes_settings( $this->data );

		if ( false === $result || 'import_fail' === $result ) {
			WP_CLI::error( sprintf( __( 'An error on import occurred', 'custom-post-type-ui' ) ) );
		} else {
			WP_CLI::success(
				sprintf(
					/* translators: Placeholders are just for HTML markup that doesn't need translated */
					__( 'Imported %s successfully', 'custom-post-type-ui' ),
					$this->type
				)
			);
		}
	}

	/**
	 * Export CPTUI settings to file.
	 *
	 * ## Options
	 *
	 * [--type=<type>]
	 * : Which settings to export. Available options are `post_type` and `taxonomy`.
	 *
	 * [--dest-path=<path>]
	 * : The path and file to export to. Relative to PWD.
	 */
	public function export( $args, $assoc_args ) {
		$this->args       = $args;
		$this->assoc_args = $assoc_args;

		if ( ! isset( $this->assoc_args['type'] ) ) {
			WP_CLI::error( __( 'Please provide whether you are exporting your post types or taxonomies', 'custom-post-type-ui' ) );
		}

		if ( ! isset( $this->assoc_args['dest-path'] ) ) {
			WP_CLI::error( __( 'Please provide a path to export your data to.', 'custom-post-type-ui' ) );
		}

		$this->type = $assoc_args['type'];

		if ( 'post_type' === $this->type ) {
			$content = cptui_get_post_type_data();
		}

		if ( 'taxonomy' === $this->type ) {
			$content = cptui_get_taxonomy_data();
		}

		$content = wp_json_encode( $content );
		$result  = file_put_contents( $this->assoc_args['dest-path'], $content );

		if ( false === $result ) {
			WP_CLI::error( __( 'Error saving data.', 'custom-post-type-ui' ) );
		}

		WP_CLI::success( __( 'Successfully saved data to file.', 'custom-post-type-ui' ) );
	}
}
WP_CLI::add_command( 'cptui', 'CPTUI_Import_JSON' );
