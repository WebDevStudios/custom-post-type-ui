<?php
require_once( 'CPTUI-Base-Tests.php' );

/**
 * An example test case.
 */
class CPTUI_Admin_UI_Core extends CPTUI_Base_Tests {

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Tests for our file being present and available.
	 */
	public function test_CPTUI_Admin_UI_Exists() {
		$this->assertFileExists( CPTUI_DIRECTORY_PATH . '/classes/class.cptui_admin_ui.php' );
	}

	/**
	 * Test that we are able to instantiate our class and get an object back.
	 */
	public function test_CPTUI_Admin_UI_Objects() {
		$ui = new cptui_admin_ui();
		$this->assertInstanceOf( 'cptui_admin_ui', $ui );
	}
}
