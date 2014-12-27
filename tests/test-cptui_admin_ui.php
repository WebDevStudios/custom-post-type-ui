<?php

/**
 * An example test case.
 */
class Test_CPTUI extends WP_UnitTestCase {

	function __construct() {
		$this->ui = new cptui_admin_ui();
	}

	/*
	 * Test that we are able to instantiate our class and get an object back.
	 */
	public function test_CPTUI_objects() {
		$this->assertInstanceOf( 'cptui_admin_ui', $this->ui );
	}

	/*
	 * Tests our class wrapper methods.
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
	 * Tests our label method.
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

	/**
	 * Tests our select input.
	 */
	public function test_CPTUI_select_no_required_no_saved() {
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

		$expected = '<tr valign="top"><th scope="row"><label for="public">Public</label><a href="#" title="Whether posts of this type should be shown in the admin UI" class="help wp-ui-highlight">?</a></th><td><select id="public" name="cpt_custom_post_type[public]"><option value="0">False</option><option value="1" selected="selected">True</option></select>(default: True)</td></tr>';

		$actual = $this->ui->get_select_input( $args );

		$this->assertEquals( $expected, $actual );
	}

	public function test_CPTUI_select_required_no_saved() {
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

		$expected = '<tr valign="top"><th scope="row"><label for="public">Public</label><span class="required">*</span><a href="#" title="Whether posts of this type should be shown in the admin UI" class="help wp-ui-highlight">?</a></th><td><select id="public" name="cpt_custom_post_type[public]"><option value="0">False</option><option value="1" selected="selected">True</option></select>(default: True)</td></tr>';

		$actual = $this->ui->get_select_input( $args );

		$this->assertEquals( $expected, $actual );
	}

	public function test_CPTUI_select_no_required_true_saved() {
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

		$expected = '<tr valign="top"><th scope="row"><label for="public">Public</label><a href="#" title="Whether posts of this type should be shown in the admin UI" class="help wp-ui-highlight">?</a></th><td><select id="public" name="cpt_custom_post_type[public]"><option value="0">False</option><option value="1" selected="selected">True</option></select>(default: True)</td></tr>';

		$actual = $this->ui->get_select_input( $args );

		$this->assertEquals( $expected, $actual );
	}

	public function test_CPTUI_select_no_required_false_saved() {
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

		$expected = '<tr valign="top"><th scope="row"><label for="public">Public</label><a href="#" title="Whether posts of this type should be shown in the admin UI" class="help wp-ui-highlight">?</a></th><td><select id="public" name="cpt_custom_post_type[public]"><option value="0" selected="selected">False</option><option value="1">True</option></select>(default: True)</td></tr>';

		$actual = $this->ui->get_select_input( $args );

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Tests non boolean based select inputs
	 */
	public function test_CPTUI_select_non_bool_no_option() {

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

		$expected = '<select id="post_type" name="cptui_selected_post_type[post_type]"><option value="">--</option><option value="movie">Movies</option><option value="tv_show">TV Show</option></select>';

		$actual = $this->ui->get_select_input( $args );

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Tests non boolean based select inputs
	 */
	public function test_CPTUI_select_non_bool_first_first_option() {

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

		$expected = '<select id="post_type" name="cptui_selected_post_type[post_type]"><option value="">--</option><option value="movie" selected="selected">Movies</option><option value="tv_show">TV Show</option></select>';

		$actual = $this->ui->get_select_input( $args );

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Tests non boolean based select inputs
	 */
	public function test_CPTUI_select_non_bool_second_option() {

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

		$expected = '<select id="post_type" name="cptui_selected_post_type[post_type]"><option value="">--</option><option value="movie">Movies</option><option value="tv_show" selected="selected">TV Show</option></select>';

		$actual = $this->ui->get_select_input( $args );

		$this->assertEquals( $expected, $actual );
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
