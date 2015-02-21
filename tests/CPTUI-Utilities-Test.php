<?php
require_once( 'CPTUI-Base-Tests.php' );

class CPTUI_Utility extends CPTUI_Base_Tests {

	public $post_type_array = array();
	public $taxonomy_array = array();

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @before
	 */
	public function setupPostTypeTax() {
		$this->post_type_array = $this->setup_cpt_option();
		$this->taxonomy_array = $this->setup_tax_option();
	}

	/**
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
	 * Tests that we have our needed post type functions
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
		$this->assertEquals( 'Parent Movie', $movie->labels->parent );
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

	}

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
	 * Create our base post type to test.
	 * @return mixed|void
	 */
	public function setup_cpt_option() {

		$custom = 'a:1:{s:5:"movie";a:21:{s:4:"name";s:5:"movie";s:5:"label";s:6:"Movies";s:14:"singular_label";s:5:"Movie";s:11:"description";s:0:"";s:6:"public";s:4:"true";s:7:"show_ui";s:4:"true";s:11:"has_archive";s:5:"false";s:19:"exclude_from_search";s:5:"false";s:15:"capability_type";s:4:"post";s:12:"hierarchical";s:5:"false";s:7:"rewrite";s:4:"true";s:12:"rewrite_slug";s:0:"";s:17:"rewrite_withfront";s:4:"true";s:9:"query_var";s:4:"true";s:13:"menu_position";s:0:"";s:12:"show_in_menu";s:4:"true";s:19:"show_in_menu_string";s:0:"";s:9:"menu_icon";N;s:8:"supports";a:0:{}s:10:"taxonomies";a:0:{}s:6:"labels";a:13:{s:9:"menu_name";s:9:"My Movies";s:9:"all_items";s:10:"All Movies";s:7:"add_new";s:7:"Add New";s:12:"add_new_item";s:13:"Add New Movie";s:4:"edit";s:4:"Edit";s:9:"edit_item";s:10:"Edit Movie";s:8:"new_item";s:9:"New Movie";s:4:"view";s:4:"View";s:9:"view_item";s:10:"View Movie";s:12:"search_items";s:13:"Search Movies";s:9:"not_found";s:15:"No Movies Found";s:18:"not_found_in_trash";s:24:"No Movies found in trash";s:6:"parent";s:12:"Parent Movie";}}}';
		update_option( 'cptui_post_types', maybe_unserialize( $custom ) );

		return get_option( 'cptui_post_types' );
	}

	/**
	 * Create our base taxonomy to test
	 * @return mixed|void
	 */
	public function setup_tax_option() {

		$custom = 'a:1:{s:6:"actors";a:14:{s:4:"name";s:6:"actors";s:5:"label";s:6:"Actors";s:14:"singular_label";s:5:"Actor";s:12:"hierarchical";s:5:"false";s:7:"show_ui";s:4:"true";s:9:"query_var";s:4:"true";s:14:"query_var_slug";s:0:"";s:7:"rewrite";s:4:"true";s:12:"rewrite_slug";s:0:"";s:17:"rewrite_withfront";s:1:"1";s:20:"rewrite_hierarchical";s:1:"0";s:17:"show_admin_column";s:5:"false";s:6:"labels";a:0:{}s:11:"object_type";a:1:{i:0;s:5:"movie";}}}';
		update_option( 'cptui_taxonomies', maybe_unserialize( $custom ) );

		return get_option( 'cptui_taxonomies' );
	}

	public function register_post_type() {
		cptui_register_single_post_type( $this->post_type_array['movie'] );
	}

	public function register_taxonomy() {
		cptui_register_single_taxonomy( $this->taxonomy_array['actors'] );
	}

}
