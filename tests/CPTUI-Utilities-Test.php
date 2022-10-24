<?php
require_once( 'CPTUI-Base-Tests.php' );

class CPTUI_Utility extends CPTUI_Base_Tests {

	public $post_type_array = [];
	public $taxonomy_array = [];

	/**
	 * Set up
	 */
	public function setUp() {
		parent::setUp();
	}

	/*
	 * Teardown.
	 */
	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Register our post type and taxonomy defaults.
	 *
	 * @before
	 */
	public function setupPostTypeTax() {
		$this->post_type_array = $this->setup_cpt_option();
		$this->taxonomy_array = $this->setup_tax_option();
	}

	/**
	 * Unregister our post type and taxonomies.
	 *
	 * @after
	 */
	public function removePostTypeTax() {
		_unregister_post_type( 'movie' );
		_unregister_taxonomy( 'actors' );
	}

	/**
	 * Tests our setting of our post type option and retrieval afterwards.
	 */
	public function test_CPTUI_set_post_type_option() {
		$this->assertTrue( is_array( $this->post_type_array ), 'Option returned false' );
		$this->assertArrayHasKey( 'movie', $this->post_type_array, 'Movie was not found in returned option value' );
	}

	/**
	 * Tests that we have our needed post type functions.
	 */
	public function test_CPTUI_post_type_registration_function_exists() {
		$this->assertTrue( function_exists( 'cptui_register_single_post_type' ) );
	}

	/**
	 * Tests for successful registering of post types with our own custom functions.
	 */
	public function test_CPTUI_registered_post_type() {
		$this->register_post_type();

		$registered = get_post_types();
		$this->assertArrayHasKey( 'movie', $registered );
	}

	/**
	 * Tests for our registered labels for the post type.
	 */
	public function test_CPTUI_registered_post_type_labels_names() {
		$this->register_post_type();

		$movie = get_post_type_object( 'movie' );
		$this->assertTrue( is_object( $movie ) );
		$this->assertTrue( is_object( $movie->labels ) );
		$this->assertNotEmpty( $movie->labels, 'No labels available' );

		$this->assertEquals( 'Movies', $movie->labels->name );
		$this->assertEquals( 'Movie', $movie->labels->singular_name );
		$this->assertEquals( 'My Movies', $movie->labels->menu_name );
		$this->assertEquals( 'All Movies', $movie->labels->all_items );
		$this->assertEquals( 'Add New', $movie->labels->add_new );
		$this->assertEquals( 'Add New Movie', $movie->labels->add_new_item );
		$this->assertEquals( 'Edit', $movie->labels->edit );
		$this->assertEquals( 'Edit Movie', $movie->labels->edit_item );
		$this->assertEquals( 'New Movie', $movie->labels->new_item );
		$this->assertEquals( 'View', $movie->labels->view );
		$this->assertEquals( 'View Movie', $movie->labels->view_item );
		$this->assertEquals( 'Search Movies', $movie->labels->search_items );
		$this->assertEquals( 'No Movies Found', $movie->labels->not_found );
		$this->assertEquals( 'No Movies found in trash', $movie->labels->not_found_in_trash );
		$this->assertEquals( 'Parent Movie', $movie->labels->parent_item_colon );
	}

	/**
	 * Tests our setting of our taxonomy option and retrieval afterwards.
	 */
	public function test_CPTUI_set_taxonomy_option() {
		$this->assertTrue( is_array( $this->taxonomy_array ), 'Option returned false' );
		$this->assertArrayHasKey( 'actors', $this->taxonomy_array, 'Actors was not found in returned option value' );
	}

	/**
	 * Tests that we have our needed taxonomy functions
	 */
	public function test_CPTUI_taxonomy_registration_function_exists() {
		$this->assertTrue( function_exists( 'cptui_register_single_taxonomy' ) );
	}

	/**
	 * Tests for successful registering of taxonomies with our own custom functions.
	 */
	public function test_CPTUI_registered_taxonomy() {
		$this->register_post_type();
		$this->register_taxonomy();

		$registered = get_taxonomies();
		$this->assertArrayHasKey( 'actors', $registered );
	}

	/**
	 * Tests for our registered labels for the taxonomy.
	 */
	public function test_CPTUI_registered_taxonomy_labels_names() {
		$this->register_post_type();
		$this->register_taxonomy();

		$actor = get_taxonomy( 'actors' );
		$this->assertTrue( is_object( $actor ) );
		$this->assertTrue( is_object( $actor->labels ) );
		$this->assertNotEmpty( $actor->labels, 'No labels available' );

		$this->assertEquals( 'Actors', $actor->labels->name );
		$this->assertEquals( 'Actor', $actor->labels->singular_name );
		$this->assertEquals( 'Actors', $actor->labels->menu_name );
		$this->assertEquals( 'All Actors', $actor->labels->all_items );
		$this->assertEquals( 'Edit Actor', $actor->labels->edit_item );
		$this->assertEquals( 'View Actor', $actor->labels->view_item );
		$this->assertEquals( 'Update Actor Name', $actor->labels->update_item );
		$this->assertEquals( 'Add New Actor', $actor->labels->add_new_item );
		$this->assertEquals( 'New Actor Name', $actor->labels->new_item_name );
		$this->assertEquals( 'Parent Actor', $actor->labels->parent_item );
		$this->assertEquals( 'Parent Actor:', $actor->labels->parent_item_colon );
		$this->assertEquals( 'Search Actors', $actor->labels->search_items );
		$this->assertEquals( 'Popular Actors', $actor->labels->popular_items );
		$this->assertEquals( 'Separate Actors with commas', $actor->labels->separate_items_with_commas );
		$this->assertEquals( 'Add or remove Actors', $actor->labels->add_or_remove_items );
		$this->assertEquals( 'Choose from the most used Actors', $actor->labels->choose_from_most_used );
		$this->assertEquals( 'No Actors found', $actor->labels->not_found );

	}

	/**
	 * Tests our return values for the get_disp_boolean helper.
	 */
	public function test_get_disp_boolean() {

		$this->assertFalse( get_disp_boolean( 0 ) );
		$this->assertFalse( get_disp_boolean( '0' ) );
		$this->assertFalse( get_disp_boolean( false ) );
		$this->assertFalse( get_disp_boolean( 'false' ) );
		$this->assertFalse( get_disp_boolean( '' ) );
		$this->assertFalse( get_disp_boolean( null ) );

		$this->assertTrue( get_disp_boolean( 1 ) );
		$this->assertTrue( get_disp_boolean( '1' ) );
		$this->assertTrue( get_disp_boolean( true ) );
		$this->assertTrue( get_disp_boolean( 'true' ) );
		$this->assertTrue( get_disp_boolean( 'abcd' ) );
		$this->assertTrue( get_disp_boolean( 1235 ) );
	}

	/**
	 * Tests our return values for the string disp_boolean helper.
	 */
	public function test_disp_boolean() {

		$this->assertEquals( 'false', disp_boolean( 0 ) );
		$this->assertEquals( 'false', disp_boolean( '0' ) );
		$this->assertEquals( 'false', disp_boolean( false ) );
		$this->assertEquals( 'false', disp_boolean( 'false' ) );
		$this->assertEquals( 'false', disp_boolean( '' ) );
		$this->assertEquals( 'false', disp_boolean( null ) );

		$this->assertEquals( 'true', disp_boolean( 1 ) );
		$this->assertEquals( 'true', disp_boolean( '1' ) );
		$this->assertEquals( 'true', disp_boolean( true ) );
		$this->assertEquals( 'true', disp_boolean( 'true' ) );
		$this->assertEquals( 'true', disp_boolean( 'abcd' ) );
		$this->assertEquals( 'true', disp_boolean( 1235 ) );

	}

	/**
	 * Tests our reserved post type utility function.
	 */
	public function test_cptui_reserved_post_types() {

		// Test without filters.
		$reserved = cptui_reserved_post_types();
		$defaults = [
			'post',
			'page',
			'attachment',
			'revision',
			'nav_menu_item',
			'action',
			'order',
			'theme',
		];

		$this->assertNotEmpty( $reserved );
		foreach ( $defaults as $default ) {
			$this->assertContains( $default, $reserved );
		}

		// Pre filters.
		$this->assertNotContains( 'reserved_slug', $reserved );
		$this->assertNotContains( 'reserved_string_slug', $reserved );

		// Add filter to get custom slugs array.
		add_filter( 'cptui_reserved_post_types', [ $this, 'add_reserved_post_types_array' ] );
		// Fetch new value with the custom slugs added.
		$reserved = cptui_reserved_post_types();
		remove_filter( 'cptui_reserved_post_types', [ $this, 'add_reserved_post_types_array' ] );

		// Need to re-test these to make sure they persist.
		$this->assertNotEmpty( $reserved );
		foreach ( $defaults as $default ) {
			$this->assertContains( $default, $reserved );
		}

		$this->assertContains( 'reserved_slug', $reserved );

		// Add filters to get custom slugs string.
		add_filter( 'cptui_reserved_post_types', [ $this, 'add_reserved_post_types_string' ] );
		// Fetch new value with the custom slugs added.
		$reserved = cptui_reserved_post_types();
		remove_filter( 'cptui_reserved_post_types', [ $this, 'add_reserved_post_types_string' ] );

		// Need to re-test these to make sure they persist.
		$this->assertNotEmpty( $reserved );
		foreach ( $defaults as $default ) {
			$this->assertContains( $default, $reserved );
		}

		$this->assertContains( 'reserved_string_slug', $reserved );
	}

	public function test_cptui_check_existing_slugs() {
		$this->register_post_type();

		register_post_type( 'foo' );

		$this->assertFalse( cptui_check_existing_post_type_slugs( false, 'tv_show', $this->post_type_array ) );
		$this->assertTrue( cptui_check_existing_post_type_slugs( false, 'page', $this->post_type_array ) );
		$this->assertTrue( cptui_check_existing_post_type_slugs( false, 'foo', $this->post_type_array ) );
	}
	/*
	 * Tests for matching page slugs.
	 */
	public function test_cptui_check_page_slugs() {
		$mismatched = 'non-matched-slug';
		$matched    = 'matched-slug';
		wp_insert_post(
			[
				'post_title' => 'My Slug',
				'post_name' => 'matched-slug',
				'post_type' => 'page',
			]
		);

		$this->assertFalse( cptui_check_page_slugs( $mismatched ) );
		$this->assertTrue( cptui_check_page_slugs( $matched ) );
	}

	/**
	 * Create our base post type to test.
	 * @return mixed|void
	 */
	public function setup_cpt_option() {

		$custom = 'a:1:{s:5:"movie";a:34:{s:4:"name";s:5:"movie";s:5:"label";s:6:"Movies";s:14:"singular_label";s:5:"Movie";s:11:"description";s:0:"";s:6:"public";s:5:"false";s:18:"publicly_queryable";s:5:"false";s:7:"show_ui";s:5:"false";s:17:"show_in_nav_menus";s:5:"false";s:16:"delete_with_user";s:4:"true";s:12:"show_in_rest";s:5:"false";s:9:"rest_base";s:5:"movie";s:21:"rest_controller_class";s:0:"";s:14:"rest_namespace";s:0:"";s:11:"has_archive";s:5:"false";s:18:"has_archive_string";s:4:"film";s:19:"exclude_from_search";s:4:"true";s:15:"capability_type";s:4:"post";s:12:"hierarchical";s:5:"false";s:10:"can_export";s:4:"true";s:7:"rewrite";s:4:"true";s:12:"rewrite_slug";s:0:"";s:17:"rewrite_withfront";s:4:"true";s:9:"query_var";s:4:"true";s:14:"query_var_slug";s:0:"";s:13:"menu_position";s:0:"";s:12:"show_in_menu";s:4:"true";s:19:"show_in_menu_string";s:0:"";s:9:"menu_icon";s:0:"";s:20:"register_meta_box_cb";N;s:8:"supports";a:3:{i:0;s:5:"title";i:1;s:6:"editor";i:2;s:9:"thumbnail";}s:10:"taxonomies";a:2:{i:0;s:8:"category";i:1;s:8:"post_tag";}s:6:"labels";a:30:{s:9:"menu_name";s:9:"My Movies";s:9:"all_items";s:10:"All Movies";s:7:"add_new";s:7:"Add new";s:12:"add_new_item";s:13:"Add new Movie";s:9:"edit_item";s:10:"Edit Movie";s:8:"new_item";s:9:"New Movie";s:9:"view_item";s:10:"View Movie";s:10:"view_items";s:11:"View Movies";s:12:"search_items";s:13:"Search Movies";s:9:"not_found";s:15:"No Movies found";s:18:"not_found_in_trash";s:24:"No Movies found in trash";s:6:"parent";s:13:"Parent Movie:";s:14:"featured_image";s:29:"Featured image for this Movie";s:18:"set_featured_image";s:33:"Set featured image for this Movie";s:21:"remove_featured_image";s:36:"Remove featured image for this Movie";s:18:"use_featured_image";s:36:"Use as featured image for this Movie";s:8:"archives";s:14:"Movie archives";s:16:"insert_into_item";s:17:"Insert into Movie";s:21:"uploaded_to_this_item";s:20:"Upload to this Movie";s:17:"filter_items_list";s:18:"Filter Movies list";s:21:"items_list_navigation";s:22:"Movies list navigation";s:10:"items_list";s:11:"Movies list";s:10:"attributes";s:17:"Movies attributes";s:14:"name_admin_bar";s:5:"Movie";s:14:"item_published";s:15:"Movie published";s:24:"item_published_privately";s:26:"Movie published privately.";s:22:"item_reverted_to_draft";s:24:"Movie reverted to draft.";s:14:"item_scheduled";s:15:"Movie scheduled";s:12:"item_updated";s:14:"Movie updated.";s:17:"parent_item_colon";s:13:"Parent Movie:";}s:15:"custom_supports";s:0:"";s:16:"enter_title_here";s:9:"Add Movie";}}';
		update_option( 'cptui_post_types', maybe_unserialize( $custom ) );

		return get_option( 'cptui_post_types' );
	}

	/**
	 * Create our base taxonomy to test
	 * @return mixed|void
	 */
	public function setup_tax_option() {

		$custom = 'a:1:{s:5:"genre";a:28:{s:4:"name";s:5:"genre";s:5:"label";s:6:"Genres";s:14:"singular_label";s:5:"Genre";s:11:"description";s:0:"";s:6:"public";s:4:"true";s:18:"publicly_queryable";s:4:"true";s:12:"hierarchical";s:5:"false";s:7:"show_ui";s:4:"true";s:12:"show_in_menu";s:4:"true";s:17:"show_in_nav_menus";s:4:"true";s:9:"query_var";s:4:"true";s:14:"query_var_slug";s:0:"";s:7:"rewrite";s:4:"true";s:12:"rewrite_slug";s:0:"";s:17:"rewrite_withfront";s:1:"1";s:20:"rewrite_hierarchical";s:1:"0";s:17:"show_admin_column";s:4:"true";s:12:"show_in_rest";s:4:"true";s:13:"show_tagcloud";s:5:"false";s:4:"sort";s:5:"false";s:18:"show_in_quick_edit";s:0:"";s:9:"rest_base";s:0:"";s:21:"rest_controller_class";s:0:"";s:14:"rest_namespace";s:0:"";s:6:"labels";a:23:{s:9:"menu_name";s:6:"Genres";s:9:"all_items";s:10:"All Genres";s:9:"edit_item";s:10:"Edit Genre";s:9:"view_item";s:10:"View Genre";s:11:"update_item";s:17:"Update Genre name";s:12:"add_new_item";s:13:"Add new Genre";s:13:"new_item_name";s:14:"New Genre name";s:11:"parent_item";s:12:"Parent Genre";s:17:"parent_item_colon";s:13:"Parent Genre:";s:12:"search_items";s:13:"Search Genres";s:13:"popular_items";s:14:"Popular Genres";s:26:"separate_items_with_commas";s:27:"Separate Genres with commas";s:19:"add_or_remove_items";s:20:"Add or remove Genres";s:21:"choose_from_most_used";s:32:"Choose from the most used Genres";s:9:"not_found";s:15:"No Genres found";s:8:"no_terms";s:9:"No Genres";s:21:"items_list_navigation";s:22:"Genres list navigation";s:10:"items_list";s:11:"Genres list";s:13:"back_to_items";s:14:"Back to Genres";s:22:"name_field_description";s:40:"The name is how it appears on your site.";s:24:"parent_field_description";s:113:"Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band";s:22:"slug_field_description";s:132:"The « slug » is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.";s:22:"desc_field_description";s:78:"The description is not prominent by default; however, some themes may show it.";}s:11:"meta_box_cb";s:0:"";s:12:"default_term";s:0:"";s:12:"object_types";a:1:{i:0;s:8:"movie";}}}';
		update_option( 'cptui_taxonomies', maybe_unserialize( $custom ) );

		return get_option( 'cptui_taxonomies' );
	}

	/**
	 * Handle registering our plugin's post type registration function.
	 */
	public function register_post_type() {
		cptui_register_single_post_type( $this->post_type_array['movie'] );
	}

	/**
	 * Handle registering our plugin's taxonomy registration function.
	 */
	public function register_taxonomy() {
		cptui_register_single_taxonomy( $this->taxonomy_array['actors'] );
	}

	/**
	 * Callback for testing cptui_reserved_post_types filter.
	 *
	 * @param array $slugs Default empty array from filter.
	 * @return array Array of custom slugs to add
	 */
	public function add_reserved_post_types_array( $slugs = [] ) {
		return [ 'reserved_slug' ];
	}

	/**
	 * Callback for testing cptui_reserved_post_types filter.
	 *
	 * @param array $slugs Default empty array from filter.
	 * @return string Slug to add to array.
	 */
	public function add_reserved_post_types_string( $slugs = [] ) {
		return 'reserved_string_slug';
	}
}
