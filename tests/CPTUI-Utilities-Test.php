<?php
require_once( 'CPTUI-Base-Tests.php' );

class CPTUI_Utility extends CPTUI_Base_Tests {

	public $post_type_array = array();
	public $taxonomy_array = array();

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
		$defaults = array(
			'post',
			'page',
			'attachment',
			'revision',
			'nav_menu_item',
			'action',
			'order',
			'theme',
		);

		$this->assertNotEmpty( $reserved );
		foreach ( $defaults as $default ) {
			$this->assertContains( $default, $reserved );
		}

		// Pre filters.
		$this->assertNotContains( 'reserved_slug', $reserved );
		$this->assertNotContains( 'reserved_string_slug', $reserved );

		// Add filter to get custom slugs array.
		add_filter( 'cptui_reserved_post_types', array( $this, 'add_reserved_post_types_array' ) );
		// Fetch new value with the custom slugs added.
		$reserved = cptui_reserved_post_types();
		remove_filter( 'cptui_reserved_post_types', array( $this, 'add_reserved_post_types_array' ) );

		// Need to re-test these to make sure they persist.
		$this->assertNotEmpty( $reserved );
		foreach ( $defaults as $default ) {
			$this->assertContains( $default, $reserved );
		}

		$this->assertContains( 'reserved_slug', $reserved );

		// Add filters to get custom slugs string.
		add_filter( 'cptui_reserved_post_types', array( $this, 'add_reserved_post_types_string' ) );
		// Fetch new value with the custom slugs added.
		$reserved = cptui_reserved_post_types();
		remove_filter( 'cptui_reserved_post_types', array( $this, 'add_reserved_post_types_string' ) );

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
			array(
				'post_title' => 'My Slug',
				'post_name' => 'matched-slug',
				'post_type' => 'page',
			)
		);

		$this->assertFalse( cptui_check_page_slugs( $mismatched ) );
		$this->assertTrue( cptui_check_page_slugs( $matched ) );
	}

	/**
	 * Create our base post type to test.
	 * @return mixed|void
	 */
	public function setup_cpt_option() {

		$custom = 'a:1:{s:5:"movie";a:22:{s:4:"name";s:5:"movie";s:5:"label";s:6:"Movies";s:14:"singular_label";s:5:"Movie";s:11:"description";s:0:"";s:6:"public";s:4:"true";s:7:"show_ui";s:4:"true";s:17:"show_in_nav_menus";s:4:"true";s:11:"has_archive";s:5:"false";s:19:"exclude_from_search";s:5:"false";s:15:"capability_type";s:4:"post";s:12:"hierarchical";s:5:"false";s:7:"rewrite";s:4:"true";s:12:"rewrite_slug";s:0:"";s:17:"rewrite_withfront";s:4:"true";s:9:"query_var";s:4:"true";s:13:"menu_position";s:0:"";s:12:"show_in_menu";s:4:"true";s:19:"show_in_menu_string";s:0:"";s:9:"menu_icon";N;s:8:"supports";a:0:{}s:10:"taxonomies";a:0:{}s:6:"labels";a:13:{s:9:"menu_name";s:9:"My Movies";s:9:"all_items";s:10:"All Movies";s:7:"add_new";s:7:"Add New";s:12:"add_new_item";s:13:"Add New Movie";s:4:"edit";s:4:"Edit";s:9:"edit_item";s:10:"Edit Movie";s:8:"new_item";s:9:"New Movie";s:4:"view";s:4:"View";s:9:"view_item";s:10:"View Movie";s:12:"search_items";s:13:"Search Movies";s:9:"not_found";s:15:"No Movies Found";s:18:"not_found_in_trash";s:24:"No Movies found in trash";s:6:"parent";s:12:"Parent Movie";}}}';
		update_option( 'cptui_post_types', maybe_unserialize( $custom ) );

		return get_option( 'cptui_post_types' );
	}

	/**
	 * Create our base taxonomy to test
	 * @return mixed|void
	 */
	public function setup_tax_option() {

		$custom = 'a:1:{s:6:"actors";a:17:{s:4:"name";s:6:"actors";s:5:"label";s:6:"Actors";s:14:"singular_label";s:5:"Actor";s:11:"description";s:12:"custom label";s:12:"hierarchical";s:5:"false";s:7:"show_ui";s:4:"true";s:9:"query_var";s:4:"true";s:14:"query_var_slug";s:0:"";s:7:"rewrite";s:4:"true";s:12:"rewrite_slug";s:0:"";s:17:"rewrite_withfront";s:1:"1";s:20:"rewrite_hierarchical";s:1:"0";s:17:"show_admin_column";s:5:"false";s:12:"show_in_rest";s:5:"false";s:9:"rest_base";s:0:"";s:6:"labels";a:15:{s:9:"menu_name";s:6:"Actors";s:9:"all_items";s:10:"All Actors";s:9:"edit_item";s:10:"Edit Actor";s:9:"view_item";s:10:"View Actor";s:11:"update_item";s:17:"Update Actor Name";s:12:"add_new_item";s:13:"Add New Actor";s:13:"new_item_name";s:14:"New Actor Name";s:11:"parent_item";s:12:"Parent Actor";s:17:"parent_item_colon";s:13:"Parent Actor:";s:12:"search_items";s:13:"Search Actors";s:13:"popular_items";s:14:"Popular Actors";s:26:"separate_items_with_commas";s:27:"Separate Actors with commas";s:19:"add_or_remove_items";s:20:"Add or remove Actors";s:21:"choose_from_most_used";s:32:"Choose from the most used Actors";s:9:"not_found";s:15:"No Actors found";}s:12:"object_types";s:0:"";}}';
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
	public function add_reserved_post_types_array( $slugs = array() ) {
		return array( 'reserved_slug' );
	}

	/**
	 * Callback for testing cptui_reserved_post_types filter.
	 *
	 * @param array $slugs Default empty array from filter.
	 * @return string Slug to add to array.
	 */
	public function add_reserved_post_types_string( $slugs = array() ) {
		return 'reserved_string_slug';
	}
}
