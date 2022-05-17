<?php
use Brain\Monkey;
use Brain\Monkey\Functions;

abstract class CPTUI_Base_Tests extends PHPUnit_Framework_TestCase {

	public function setUp() {
		parent::setUp();

		// Given functions will return the first argument they will receive,
		// just like `when( $function_name )->justReturnArg()` was used for all of them.
		Functions\stubs(
			[
				'esc_attr',
				'esc_attr__',
				'esc_html',
				'esc_textarea',
				'__',
				'_x',
				'esc_html__',
				'esc_html_x',
				'esc_attr_x',
			]
		);
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function assertHTMLstringsAreEqual( $expected_string, $string_to_test ) {
		$expected_string = $this->normalize_string( $expected_string );
		$string_to_test = $this->normalize_string( $string_to_test );
		$compare = strcmp( $expected_string, $string_to_test );
		if ( 0 !== $compare ) {
			$compare       = strspn( $expected_string ^ $string_to_test, "\0" );
			$chars_to_show = 50;
			$start         = ( $compare - 5 );
			$pointer       = '|--->>';
			$sep           = "\n". str_repeat( '-', 75 );
			$compare = sprintf(
			    $sep . "\nFirst difference at position %d:\n\n  Expected: \t%s\n  Actual: \t%s\n" . $sep,
			    $compare,
			    substr( $expected_string, $start, 5 ) . $pointer . substr( $expected_string, $compare, $chars_to_show ),
			    substr( $string_to_test, $start, 5 ) . $pointer . substr( $string_to_test, $compare, $chars_to_show )
			);
		}
		return $this->assertEquals( $expected_string, $string_to_test, ! empty( $compare ) ? $compare : null );
	}


	public function assertIsDefined( $definition ) {
		return $this->assertTrue( defined( $definition ), "$definition is not defined." );
	}

	public function normalize_string( $string ) {
		return trim( preg_replace( array(
			'/[\t\n\r]/', // Remove tabs and newlines
			'/\s{2,}/', // Replace repeating spaces with one space
			'/> </', // Remove spaces between carats
		), array(
			'',
			' ',
			'><',
		), $string ) );
	}
}
