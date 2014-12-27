<?php

/**
 * An example test case.
 */
class CPTUI_Admin_UI_Wrappers extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Tests our opening tr method.
	 * @test
	 */
	public function CPTUI_Opening_TR() {
		$ui       = new cptui_admin_ui();
		$expected = '<tr valign="top">';

		$this->assertEquals( $expected, $ui->get_tr_start() );
	}

	/**
	 * Tests our closing tr method.
	 * @test
	 */
	public function CPTUI_Closing_TR() {
		$ui       = new cptui_admin_ui();
		$expected = '</tr>';

		$this->assertEquals( $expected, $ui->get_tr_end() );
	}

	/**
	 * Tests our opening th method.
	 * @test
	 */
	public function CPTUI_Opening_TH() {
		$ui       = new cptui_admin_ui();
		$expected = '<th scope="row">';

		$this->assertEquals( $expected, $ui->get_th_start() );
	}

	/**
	 * Tests our closing th method.
	 * @test
	 */
	public function CPTUI_Closing_TH() {
		$ui       = new cptui_admin_ui();
		$expected = '</th>';

		$this->assertEquals( $expected, $ui->get_th_end() );
	}

	/**
	 * Tests our opening td method.
	 * @test
	 */
	public function CPTUI_Opening_TD() {
		$ui       = new cptui_admin_ui();
		$expected = '<td>';

		$this->assertEquals( $expected, $ui->get_td_start() );
	}

	/**
	 * Tests our closing td method.
	 * @test
	 */
	public function CPTUI_Closing_TD() {
		$ui       = new cptui_admin_ui();
		$expected = '</td>';

		$this->assertEquals( $expected, $ui->get_td_end() );
	}

	/**
	 * Tests our wrapping p tag.
	 * @test
	 */
	public function CPTUI_P_Wrap() {
		$ui       = new cptui_admin_ui();
		$expected = '<p>CPTUI is Awesome!</p>';

		$this->assertEquals( $expected, $ui->get_p( 'CPTUI is Awesome!' ) );

	}
}
