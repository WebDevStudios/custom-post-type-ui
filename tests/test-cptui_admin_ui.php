<?php

/**
 * An example test case.
 */
class Test_CPTUI extends WP_UnitTestCase {

	function __construct() {
		$this->ui = new cptui_admin_ui();
	}

	/*
	Test that we are able to instantiate our class and get an object back.
	 */
	public function test_CPTUI_objects() {
		$this->assertInstanceOf( 'cptui_admin_ui', $this->ui );
	}

	/*
	These tests just assert proper markup for our wrappers to go with the inputs.
	 */
	public function test_CPTUI_wrappers() {
        $o_tr   = '<tr valign="top">';
        $c_tr   = '</tr>';
        $o_th   = '<th scope="row">';
        $c_th   = '</th>';
        $o_td   = '<td>';
        $c_td   = '</td>';
        $p      = '<p>Paragraph</p>';

		$this->assertEquals( $o_tr, $this->ui->get_tr_start() );
		$this->assertEquals( $c_tr, $this->ui->get_tr_end() );
		$this->assertEquals( $o_th, $this->ui->get_th_start() );
		$this->assertEquals( $c_th, $this->ui->get_th_end() );
		$this->assertEquals( $o_td, $this->ui->get_td_start() );
		$this->assertEquals( $c_td, $this->ui->get_td_end() );
		$this->assertEquals( $p, $this->ui->get_p( 'Paragraph' ) );

	}

	/*

	 */
	public function test_CPTUI_label() {
		$label = '<label for="testing">Testing</label>';

		$this->assertEquals( $label, $this->ui->get_label( 'testing', 'Testing' ) );
	}

	public function test_CPTUI_required() {
		$required = '<span class="required">*</span>';

		$this->assertEquals( $required, $this->ui->get_required() );
	}

	public function test_CPTUI_textarea() {
		$result = '<tr valign="top"><th scope="row"><label for="name">Description</label><a href="#" title="Helper text." class="help wp-ui-highlight">?</a></th><td><textarea id="name" name="name_array[name]" rows="4" cols="40">saved value</textarea></td></tr>';
		$args = array(
			'namearray' => 'name_array',
			'name' => 'name',
			'rows' => '4',
			'cols' => '40',
			'textvalue' => 'saved value',
			'labeltext' => 'Description',
			'helptext' => 'Helper text.'
		);

		$this->assertEquals( $result, $this->ui->get_textarea_input( $args ) );
	}

	public function test_CPTUI_text() {
		$result = '<tr valign="top"><th scope="row"><label for="name">Description</label><a href="#" title="Helper text." class="help wp-ui-highlight">?</a></th><td><input type="text" id="name" name="name_array[name]" value="saved value" /><br/></td></tr>';

		$args = array(
			'namearray' => 'name_array',
			'name' => 'name',
			'rows' => '4',
			'cols' => '40',
			'textvalue' => 'saved value',
			'labeltext' => 'Description',
			'helptext' => 'Helper text.'
		);

		$this->assertEquals( $result, $this->ui->get_text_input( $args ) );
	}


	public function providertest_something()
	{
		return array(
			array('This string will be sluggified', 'this-string-will-be-sluggified'),
			array('THIS STRING WILL BE SLUGGIFIED', 'this-string-will-be-sluggified'),
			array('This1 string2 will3 be 44 sluggified10', 'this1-string2-will3-be-44-sluggified10'),
			array('This! @string#$ %$will ()be "sluggified', 'this-string-will-be-sluggified'),
			array("Tänk efter nu – förr'n vi föser dig bort", 'tank-efter-nu-forrn-vi-foser-dig-bort'),
			array('', ''),
		);
	}

}
/*
 * TODO: data providers
 * @dataProvider providertest_something
 */
