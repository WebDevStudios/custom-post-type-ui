<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Content for the Get Code tab.
 * @since 1.2.0
 * @internal
 */
function cptui_render_getcode_section() {
	?>
	<h1><?php esc_html_e( 'Get Post Type and Taxonomy Code', 'custom-post-type-ui' ); ?></h1>

	<h2><?php esc_html_e( 'All Custom Post Type UI Post Types', 'custom-post-type-ui' ); ?></h2>

	<p><?php esc_html_e( 'All of the selectable code snippets below are useful if you wish to migrate away from Custom Post Type UI and retain your existing registered post types or taxonomies.', 'custom-post-type-ui' ); ?></p>

	<?php $cptui_post_types = cptui_get_post_type_data(); ?>
	<p>
		<label for="cptui_post_type_get_code"><?php esc_html_e( 'Copy/paste the code below into your functions.php file.', 'custom-post-type-ui' ); ?></label>
	</p>
	<textarea name="cptui_post_type_get_code" id="cptui_post_type_get_code" class="large-text cptui_post_type_get_code" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true"><?php cptui_get_post_type_code( $cptui_post_types ); ?></textarea>

	<?php
	if ( ! empty( $cptui_post_types ) ) {
		foreach ( $cptui_post_types as $post_type ) {
			?>
			<h2 id="<?php echo esc_attr( $post_type['name'] ); ?>">
				<?php
				$type = ! empty( $post_type['label'] ) ? esc_html( $post_type['label'] ) : esc_html( $post_type['name'] );
				printf( esc_html__( '%s Post Type', 'custom-post-type-ui' ), esc_html( $type ) ); ?></h2>
			<p>
				<label for="cptui_post_type_get_code_<?php echo esc_attr( $post_type['name'] ); ?>"><?php esc_html_e( 'Copy/paste the code below into your functions.php file.', 'custom-post-type-ui' ); ?></label>
			</p>
			<textarea name="cptui_post_type_get_code_<?php echo esc_attr( $post_type['name'] ); ?>" id="cptui_post_type_get_code_<?php echo esc_attr( $post_type['name'] ); ?>" class="large-text cptui_post_type_get_code" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true"><?php cptui_get_post_type_code( [ $post_type ], true ); ?></textarea>
			<?php
		}
	}
	?>

	<h2><?php esc_html_e( 'All Custom Post Type UI Taxonomies', 'custom-post-type-ui' ); ?></h2>

	<?php $cptui_taxonomies = cptui_get_taxonomy_data(); ?>
	<p>
		<label for="cptui_tax_get_code"><?php esc_html_e( 'Copy/paste the code below into your functions.php file.', 'custom-post-type-ui' ); ?></label>
	</p>
	<textarea name="cptui_tax_get_code" id="cptui_tax_get_code" class="large-text cptui_tax_get_code" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true"><?php cptui_get_taxonomy_code( $cptui_taxonomies ); ?></textarea>

	<?php
	if ( ! empty( $cptui_taxonomies ) ) {
		foreach ( $cptui_taxonomies as $taxonomy ) {
			?>
			<h2 id="<?php echo esc_attr( $taxonomy['name'] ); ?>">
				<?php
				$tax = ! empty( $taxonomy['label'] ) ? esc_html( $taxonomy['label'] ) : esc_html( $taxonomy['name'] );
				printf( esc_html__( '%s Taxonomy', 'custom-post-type-ui' ), esc_html( $tax ) );
				?>
			</h2>
			<p>
				<label for="cptui_tax_get_code_<?php echo esc_attr( $taxonomy['name'] ); ?>"><?php esc_html_e( 'Copy/paste the code below into your functions.php file.', 'custom-post-type-ui' ); ?></label>
			</p>
			<textarea name="cptui_tax_get_code_<?php echo esc_attr( $taxonomy['name'] ); ?>" id="cptui_tax_get_code_<?php echo esc_attr( $taxonomy['name'] ); ?>" class="large-text cptui_tax_get_code" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true"><?php cptui_get_taxonomy_code( [ $taxonomy ], true ); ?></textarea>
			<?php
		}
	}
	?>
	<?php
}
