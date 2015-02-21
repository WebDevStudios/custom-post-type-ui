<?php
require_once( 'CPTUI-Base-Tests.php' );

/**
 * An example test case.
 */
class CPTUI_Admin_UI_Inputs_Test extends CPTUI_Base_Tests {

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Tests our label method.
	 */
	public function test_CPTUI_Label() {
		$ui = new cptui_admin_ui();
		$expected = '<label for="testing">Testing</label>';

		$this->assertEquals( $expected, $ui->get_label( 'testing', 'Testing' ) );
	}

	/**
	 * Tests our required field method.
	 */
	public function test_CPTUI_Required() {
		$ui = new cptui_admin_ui();
		$expected = '<span class="required">*</span>';

		$this->assertEquals( $expected, $ui->get_required() );
	}

	/**
	 * Tests our textarea field method.
	 */
	public function test_CPTUI_Textarea() {
		$ui = new cptui_admin_ui();

		$expected = '<tr valign="top">
			<th scope="row">
				<label for="name">Description</label>
				<a href="#" title="Helper text." class="cptui-help wp-ui-highlight">?</a>
			</th>
			<td>
				<textarea id="name" name="name_array[name]" rows="4" cols="40">saved value</textarea>
			</td>
		</tr>';

		$args = array(
			'namearray' => 'name_array',
			'name' => 'name',
			'rows' => '4',
			'cols' => '40',
			'textvalue' => 'saved value',
			'labeltext' => 'Description',
			'helptext' => 'Helper text.'
		);

		$this->assertHTMLstringsAreEqual( $expected, $ui->get_textarea_input( $args ) );
	}

	/**
	 * Tests our text field method.
	 */
	public function test_CPTUI_Text() {
		$ui = new cptui_admin_ui();

		$expected = '<tr valign="top">
			<th scope="row">
				<label for="name">Description</label>
				<a href="#" title="Helper text." class="cptui-help wp-ui-highlight">?</a>
			</th>
			<td>
				<input type="text" id="name" name="name_array[name]" value="saved value" /><br/>
			</td>
		</tr>';

		$args = array(
			'namearray' => 'name_array',
			'name' => 'name',
			'rows' => '4',
			'cols' => '40',
			'textvalue' => 'saved value',
			'labeltext' => 'Description',
			'helptext' => 'Helper text.'
		);

		$this->assertHTMLstringsAreEqual( $expected, $ui->get_text_input( $args ) );
	}

	/**
	 * Tests our select input.
	 *
	 * This test checks for no required boolean and no saved value.
	 */
	public function test_CPTUI_Select_No_Required_No_Saved() {
		$ui = new cptui_admin_ui();

		$select = array(
			'options' => array(
				array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
				array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
			)
		);

		$select['selected'] = '';
		$args = array(
			'namearray'     => 'cpt_custom_post_type',
			'name'          => 'public',
			'labeltext'     => __( 'Public', 'cpt-plugin' ),
			'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
			'helptext'      => esc_attr__( 'Whether posts of this type should be shown in the admin UI', 'cpt-plugin' ),
			'selections'    => $select
		);

		$expected = '<tr valign="top">
			<th scope="row">
				<label for="public">Public</label>
				<a href="#" title="Whether posts of this type should be shown in the admin UI" class="cptui-help wp-ui-highlight">?</a>
			</th>
			<td>
				<select id="public" name="cpt_custom_post_type[public]">
					<option value="0">False</option>
					<option value="1" selected="selected">True</option>
				</select>
				(default: True)
			</td>
		</tr>';

		$this->assertHTMLstringsAreEqual( $expected, $ui->get_select_input( $args ) );
	}

	/**
	 * Tests our select input.
	 *
	 * This test checks for no required boolean and no saved value.
	 */
	public function test_CPTUI_Select_Required_No_Saved() {
		$ui = new cptui_admin_ui();

		$select = array(
			'options' => array(
				array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
				array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
			)
		);

		$select['selected'] = '';
		$args = array(
			'namearray'     => 'cpt_custom_post_type',
			'name'          => 'public',
			'labeltext'     => __( 'Public', 'cpt-plugin' ),
			'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
			'helptext'      => esc_attr__( 'Whether posts of this type should be shown in the admin UI', 'cpt-plugin' ),
			'selections'    => $select,
			'required'      => true
		);

		$expected = '<tr valign="top">
			<th scope="row">
				<label for="public">Public</label><span class="required">*</span>
				<a href="#" title="Whether posts of this type should be shown in the admin UI" class="cptui-help wp-ui-highlight">?</a>
			</th>
			<td>
				<select id="public" name="cpt_custom_post_type[public]">
					<option value="0">False</option>
					<option value="1" selected="selected">True</option>
				</select>
				(default: True)
			</td>
		</tr>';

		$this->assertHTMLstringsAreEqual( $expected, $ui->get_select_input( $args ) );
	}

	/**
	 * Tests our select input.
	 *
	 * This test checks for no required boolean and a "true" saved value.
	 */
	public function test_CPTUI_Select_No_Required_True_Saved() {
		$ui = new cptui_admin_ui();

		$select = array(
			'options' => array(
				array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
				array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
			)
		);

		$select['selected'] = '1';
		$args = array(
			'namearray'     => 'cpt_custom_post_type',
			'name'          => 'public',
			'labeltext'     => __( 'Public', 'cpt-plugin' ),
			'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
			'helptext'      => esc_attr__( 'Whether posts of this type should be shown in the admin UI', 'cpt-plugin' ),
			'selections'    => $select,
		);

		$expected = '<tr valign="top">
			<th scope="row">
				<label for="public">Public</label>
		<a href="#" title="Whether posts of this type should be shown in the admin UI" class="cptui-help wp-ui-highlight">?</a>
			</th>
			<td>
				<select id="public" name="cpt_custom_post_type[public]">
					<option value="0">False</option>
					<option value="1" selected="selected">True</option>
				</select>
				(default: True)
			</td>
		</tr>';

		$this->assertHTMLstringsAreEqual( $expected, $ui->get_select_input( $args ) );
	}

	/**
	 * Tests our select input.
	 *
	 * This test checks for no required boolean and a "false" saved value.
	 */
	public function test_CPTUI_Select_No_Required_False_Saved() {
		$ui = new cptui_admin_ui();

		$select = array(
			'options' => array(
				array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
				array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
			)
		);

		$select['selected'] = '0';
		$args = array(
			'namearray'     => 'cpt_custom_post_type',
			'name'          => 'public',
			'labeltext'     => __( 'Public', 'cpt-plugin' ),
			'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
			'helptext'      => esc_attr__( 'Whether posts of this type should be shown in the admin UI', 'cpt-plugin' ),
			'selections'    => $select,
		);

		$expected = '<tr valign="top">
			<th scope="row">
				<label for="public">Public</label>
				<a href="#" title="Whether posts of this type should be shown in the admin UI" class="cptui-help wp-ui-highlight">?</a>
			</th>
			<td>
				<select id="public" name="cpt_custom_post_type[public]">
					<option value="0" selected="selected">False</option>
					<option value="1">True</option>
				</select>
				(default: True)
			</td>
		</tr>';

		$this->assertHTMLstringsAreEqual( $expected, $ui->get_select_input( $args ) );
	}

	/**
	 * Tests non boolean based select inputs
	 */
	public function test_CPTUI_Select_Non_Bool_No_Option() {
		$ui = new cptui_admin_ui();

		$select = array();
		$select['options'] = array();

		$select['options'][] = array( 'attr' => '', 'text' => '--' );
		$select['options'][] = array( 'attr' => 'movie', 'text' => 'Movies' );
		$select['options'][] = array( 'attr' => 'tv_show', 'text' => 'TV Show' );

		$select['selected'] = '';
		$args = array(
			'namearray'     => 'cptui_selected_post_type',
			'name'          => 'post_type',
			'selections'    => $select,
			'wrap'          => false
		);

		$expected = '<select id="post_type" name="cptui_selected_post_type[post_type]">
			<option value="">--</option>
			<option value="movie">Movies</option>
			<option value="tv_show">TV Show</option>
		</select>';

		$this->assertHTMLstringsAreEqual( $expected, $ui->get_select_input( $args ) );
	}

	/**
	 * Tests non boolean based select inputs
	 */
	public function test_CPTUI_Select_Non_Bool_First_Option() {
		$ui = new cptui_admin_ui();

		$select = array();
		$select['options'] = array();

		$select['options'][] = array( 'attr' => '', 'text' => '--' );
		$select['options'][] = array( 'attr' => 'movie', 'text' => 'Movies' );
		$select['options'][] = array( 'attr' => 'tv_show', 'text' => 'TV Show' );

		$select['selected'] = 'movie';
		$args = array(
			'namearray'     => 'cptui_selected_post_type',
			'name'          => 'post_type',
			'selections'    => $select,
			'wrap'          => false
		);

		$expected = '<select id="post_type" name="cptui_selected_post_type[post_type]">
			<option value="">--</option>
			<option value="movie" selected="selected">Movies</option>
			<option value="tv_show">TV Show</option>
		</select>';

		$this->assertHTMLstringsAreEqual( $expected, $ui->get_select_input( $args ) );
	}

	/**
	 * Tests non boolean based select inputs
	 */
	public function test_CPTUI_Select_Non_Bool_Second_Option() {
		$ui = new cptui_admin_ui();

		$select = array();
		$select['options'] = array();

		$select['options'][] = array( 'attr' => '', 'text' => '--' );
		$select['options'][] = array( 'attr' => 'movie', 'text' => 'Movies' );
		$select['options'][] = array( 'attr' => 'tv_show', 'text' => 'TV Show' );

		$select['selected'] = 'tv_show';
		$args = array(
			'namearray'     => 'cptui_selected_post_type',
			'name'          => 'post_type',
			'selections'    => $select,
			'wrap'          => false
		);

		$expected = '<select id="post_type" name="cptui_selected_post_type[post_type]">
			<option value="">--</option>
			<option value="movie">Movies</option>
			<option value="tv_show" selected="selected">TV Show</option>
		</select>';

		$this->assertHTMLstringsAreEqual( $expected, $ui->get_select_input( $args ) );
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
