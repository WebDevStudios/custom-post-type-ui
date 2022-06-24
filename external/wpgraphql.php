<?php

namespace CPTUI;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class creates settings for Custom Post Type UI to show Custom Post Types in GraphQL
 */
class CPTUI_GraphQL {

	/**
	 * @var bool
	 */
	protected $show_in_graphql = false;

	/**
	 * @var string
	 */
	protected $graphql_single_name = '';

	/**
	 * @var string
	 */
	protected $graphql_plural_name = '';

	/**
	 * Initializes the plugin functionality
	 */
	public function init() {

		// Post Types.
		add_action( 'cptui_post_type_after_fieldsets', [ $this, 'add_graphql_post_type_settings' ], 10, 1 );
		add_filter( 'cptui_before_update_post_type', [ $this, 'before_update_post_type' ], 10, 2 );
		add_filter( 'cptui_pre_register_post_type', [ $this, 'add_graphql_settings_to_registry' ], 10, 3 );
		add_filter( 'cptui_pre_save_post_type', [ $this, 'save_graphql_settings' ], 10, 2 );

		// Taxonomies.
		add_action( 'cptui_taxonomy_after_fieldsets', [ $this, 'add_taxonomy_graphql_settings' ], 10, 1 );
		add_filter( 'cptui_before_update_taxonomy', [ $this, 'before_update_taxonomy' ], 10, 2 );
		add_filter( 'cptui_pre_register_taxonomy', [ $this, 'add_graphql_settings_to_registry' ], 10, 3 );
		add_filter( 'cptui_pre_save_taxonomy', [ $this, 'save_graphql_settings' ], 10, 2 );
	}

	/**
	 * Adds the GraphQL Settings from CPT UI to the post_type and taxonomy registry args.
	 *
	 * @param array  $args The args for the registry.
	 * @param string $name The name of the type.
	 * @param array  $type The array that composes the Type.
	 *
	 * @return array
	 */
	public function add_graphql_settings_to_registry( $args, $name, $type ) {

		// If the type is not set to show_in_graphql, return the args as-is.
		if ( ! isset( $type['show_in_graphql'] ) || true !== (bool) $type['show_in_graphql'] ) {
			return $args;
		}

		// If the type has no graphql_plural_name, return the args as-is, but
		// add a message to the debug log for why the Type is not in the Schema.
		if ( ! isset( $type['graphql_plural_name'] ) || empty( $type['graphql_plural_name'] ) ) {
			graphql_debug(
				sprintf(
					// phpcs:ignore.
					esc_attr__( 'The graphql_plural_name is empty for the "%s" Post Type or Taxonomy registered by Custom Post Type UI.' ),
					$type['name']
					)
				);

			return $args;
		}

		// If the type has no graphql_single_name, return the args as-is, but
		// add a message to the debug log for why the Type is not in the Schema.
		if ( ! isset( $type['graphql_single_name'] ) || empty( $type['graphql_single_name'] ) ) {
			graphql_debug(
				sprintf(
					// phpcs:ignore.
					esc_attr__( 'The graphql_single_name is empty for the "%s" Post Type or Taxonomy registered by Custom Post Type UI.' ),
					$type['name']
				)
			);

			return $args;
		}

		$args['show_in_graphql']     = isset( $type['show_in_graphql'] ) ? (bool) $type['show_in_graphql'] : false;
		$args['graphql_single_name'] = ! empty( $type['graphql_single_name'] ) ? $type['graphql_single_name'] : null;
		$args['graphql_plural_name'] = ! empty( $type['graphql_plural_name'] ) ? $type['graphql_plural_name'] : null;

		return $args;
	}

	/**
	 * Capture post type settings from form submission for saving
	 *
	 * @param array $data
	 */
	public function before_update_post_type( $data ) {
		$this->show_in_graphql     = isset( $data['cpt_custom_post_type']['show_in_graphql'] ) ? $data['cpt_custom_post_type']['show_in_graphql'] : false;
		$this->graphql_single_name = isset( $data['cpt_custom_post_type']['graphql_single_name'] ) ? \WPGraphQL\Utils\Utils::format_type_name( $data['cpt_custom_post_type']['graphql_single_name'] ) : '';
		$this->graphql_plural_name = isset( $data['cpt_custom_post_type']['graphql_plural_name'] ) ? \WPGraphQL\Utils\Utils::format_type_name( $data['cpt_custom_post_type']['graphql_plural_name'] ) : '';
	}

	/**
	 * Capture taxonomy settings from form submission for saving
	 *
	 * @param array $data
	 */
	public function before_update_taxonomy( $data ) {
		$this->show_in_graphql     = isset( $data['cpt_custom_tax']['show_in_graphql'] ) ? $data['cpt_custom_tax']['show_in_graphql'] : false;
		$this->graphql_single_name = isset( $data['cpt_custom_tax']['graphql_single_name'] ) ? \WPGraphQL\Utils\Utils::format_type_name( $data['cpt_custom_tax']['graphql_single_name'] ) : '';
		$this->graphql_plural_name = isset( $data['cpt_custom_tax']['graphql_plural_name'] ) ? \WPGraphQL\Utils\Utils::format_type_name( $data['cpt_custom_tax']['graphql_plural_name'] ) : '';
	}

	/**
	 * Save values from form submission
	 *
	 * @param array  $type
	 * @param string $name
	 *
	 * @return array
	 */
	public function save_graphql_settings( $type, $name ) {
		$type[ $name ]['show_in_graphql']     = $this->show_in_graphql;
		$type[ $name ]['graphql_single_name'] = \WPGraphQL\Utils\Utils::format_type_name( $this->graphql_single_name );
		$type[ $name ]['graphql_plural_name'] = \WPGraphQL\Utils\Utils::format_type_name( $this->graphql_plural_name );

		return $type;
	}

	/**
	 * Add settings fields to Custom Post Type UI form
	 *
	 * @param cptui_admin_ui $ui Admin UI instance.
	 */
	public function add_graphql_post_type_settings( $ui ) {
		$tab        = ( ! empty( $_GET ) && ! empty( $_GET['action'] ) && 'edit' === $_GET['action'] ) ? 'edit' : 'new'; // phpcs:ignore WordPress.Security.NonceVerification
		$current    = [];
		$name_array = 'cpt_custom_post_type';
		if ( 'edit' === $tab ) {
			$post_types         = cptui_get_post_type_data();
			$selected_post_type = cptui_get_current_post_type( false );
			if ( $selected_post_type ) {
				if ( array_key_exists( $selected_post_type, $post_types ) ) {
					$current = $post_types[ $selected_post_type ];
				}
			}
		}
		echo $this->get_setting_fields( $ui, $current, $name_array ); // phpcs:ignore.
	}

	/**
	 * Add settings fields to Custom Post Type UI form
	 *
	 * @param cptui_admin_ui $ui Admin UI instance.
	 */
	public function add_taxonomy_graphql_settings( $ui ) {
		$tab        = ( ! empty( $_GET ) && ! empty( $_GET['action'] ) && 'edit' === $_GET['action'] ) ? 'edit' : 'new'; // phpcs:ignore WordPress.Security.NonceVerification
		$name_array = 'cpt_custom_tax';
		$current    = [];
		if ( 'edit' === $tab ) {
			$taxonomies        = cptui_get_taxonomy_data();
			$selected_taxonomy = cptui_get_current_taxonomy( false );
			if ( $selected_taxonomy ) {
				if ( array_key_exists( $selected_taxonomy, $taxonomies ) ) {
					$current = $taxonomies[ $selected_taxonomy ];
				}
			}
		}
		echo $this->get_setting_fields( $ui, $current, $name_array ); // phpcs:ignore.
	}

	/**
	 * Get the settings fields to render for the form
	 *
	 * @param cptui_admin_ui $ui Admin UI instance.
	 * @param array          $current
	 * @param string         $name_array
	 */
	public function get_setting_fields( $ui, $current, $name_array ) {
		?>
		<div class="cptui-section postbox">
			<div class="postbox-header">

				<h2 class="hndle ui-sortable-handle">
					<span><?php esc_html_e( 'WPGraphQL', 'wp-graphql-custom-post-type-ui' ); ?></span>
				</h2>
				<div class="handle-actions hide-if-no-js">
					<button type="button" class="handlediv">
						<span class="screen-reader-text"><?php esc_html_e( 'Toggle panel: GraphQL Settings', 'wp-graphql-custom-post-type-ui' ); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>
				</div>
			</div>
			<div class="inside">
				<div class="main">
					<table class="form-table cptui-table">
						<?php

						$selections = [
							'options' => [
								[
									'attr' => '0',
									'text' => esc_attr__( 'False', 'wp-graphql-custom-post-type-ui' ),
								],
								[
									'attr' => '1',
									'text' => esc_attr__( 'True', 'wp-graphql-custom-post-type-ui' ),
								],
							],
						];

						$selected               = ( isset( $current ) && ! empty( $current['show_in_graphql'] ) ) ? disp_boolean( $current['show_in_graphql'] ) : '';
						$selections['selected'] = ( ! empty( $selected ) && ! empty( $current['show_in_graphql'] ) ) ? $current['show_in_graphql'] : '0';

						echo $ui->get_select_input( // phpcs:ignore.
							[
								'namearray'  => $name_array,
								'name'       => 'show_in_graphql',
								'labeltext'  => esc_html__( 'Show in GraphQL', 'wp-graphql-custom-post-type-ui' ),
								'aftertext'  => esc_html__( 'Whether or not to show data of this type in the WPGraphQL. Default: false', 'wp-graphql-custom-post-type-ui' ),
								'selections' => $selections, // phpcs:ignore.
								'default'    => false,
								'required'   => true,
							]
						);

						echo $ui->get_text_input( // phpcs:ignore.
							[
								'namearray' => $name_array,
								'name'      => 'graphql_single_name',
								'labeltext' => esc_html__( 'GraphQL Single Name', 'wp-graphql-custom-post-type-ui' ),
								'aftertext' => esc_attr__( 'Singular name for reference in the GraphQL API.', 'wp-graphql-custom-post-type-ui' ),
								'textvalue' => ( isset( $current['graphql_single_name'] ) ) ? esc_attr( $current['graphql_single_name'] ) : '', // phpcs:ignore.
								'required'  => true,
							]
						);

						echo $ui->get_text_input( // phpcs:ignore.
							[
								'namearray' => $name_array,
								'name'      => 'graphql_plural_name',
								'labeltext' => esc_html__( 'GraphQL Plural Name', 'wp-graphql-custom-post-type-ui' ),
								'aftertext' => esc_attr__( 'Plural name for reference in the GraphQL API.', 'wp-graphql-custom-post-type-ui' ),
								'textvalue' => ( isset( $current['graphql_plural_name'] ) ) ? esc_attr( $current['graphql_plural_name'] ) : '', // phpcs:ignore.
								'required'  => true,
							]
						);
						?>
					</table>
				</div>
			</div>
		</div>
		<?php
		$this->graphql_field_helpers();
	}

	/**
	 * JavaScript helpers to add conditional logic and support for the GraphQL setting fields
	 */
	public function graphql_field_helpers() {
		// This script provides helpers for the GraphQL fields in the CPT UI screen.
		// If the Post Type or Taxonomy is not set to show_in_graphql the single/plural names
		// should not be required.
		?>
		<script type="application/javascript">
			let singleName = document.getElementById('graphql_single_name');
			let singleNameRow = singleName.closest('tr');
			let pluralName = document.getElementById('graphql_plural_name');
			let pluralNameRow = pluralName.closest('tr');
			let showInGraphQL = document.getElementById('show_in_graphql');
			let label = document.getElementById('label');
			let singleLabel = document.getElementById('singular_label');

			// Set the values of the GraphQL fields and their display state
			function updateGraphQlFields() {

				// Set default state for field values and display state
				// If the show_in_graphql value is true (or '1') show the
				// fields and set them as required
				// Else hide the fields and leave them as not-required
				if (showInGraphQL.value === '1') {
					singleName.required = true;
					pluralName.required = true;
					pluralNameRow.style.display = "table-row";
					singleNameRow.style.display = "table-row";
				} else {
					singleName.required = false;
					pluralName.required = false;
					pluralNameRow.style.display = "none";
					singleNameRow.style.display = "none";
				}

				// If single_name has no value, but single_label does, use single_label as the value
				if (!singleName.value.length) {
					singleName.value = singleLabel.value;
				}

				// If single_name has no value, but single_label does, use single_label as the value
				if (!pluralName.value.length) {
					pluralName.value = label.value;
				}
			}

			// Once the DOM is ready, listen for events
			document.addEventListener("DOMContentLoaded", function () {
				updateGraphQlFields();
				// When the show in graphql field changes, re-apply GraphQL Field Values
				showInGraphQL.addEventListener('input', function () {
					updateGraphQlFields();
				});
			});
		</script>
		<?php
	}
}

/**
 * Load WPGraphQL for CPT UI
 */
add_action( 'cptui_loaded', __NAMESPACE__ . '\cptui_graphql_init' );

function cptui_graphql_init() {
	if ( class_exists( 'WPGraphQL_CPT_UI' ) ) {
		add_action(
			'admin_notices',
			function () {
				$link = trailingslashit( admin_url() ) . 'plugins.php';
				?>
			<div class="notice notice-error">
				<p>
				<?php
				echo sprintf(
					// phpcs:ignore.
					esc_html__( 'Custom Post Type UI has native support for WPGraphQL. Please <a href="%s">de-active</a> the "WPGraphQL for Custom Post Type UI" extension to proceed.', 'custom-post-type-ui' ),
					$link // phpcs:ignore.
				);
				?>
					</p>
			</div>
				<?php
			}
		);

		return;
	}

	$wpgraphql_cpt_ui = new CPTUI_GraphQL();
	$wpgraphql_cpt_ui->init();
}
