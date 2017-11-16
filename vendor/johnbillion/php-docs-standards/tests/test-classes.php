<?php
namespace Johnbillion\DocsStandards\Tests;

class Classes extends TestCase {

	/**
	 * Setup method.
	 */
	public function setUp() {
		parent::setUp();
		require_once __DIR__ . '/includes/test-classes.php';
	}

	/**
	 * Test that traits can be tested, and that all their methods are tested.
	 */
	public function testTraitMethodsAreTestedWhenTestingATrait() {
		$case = new My_Trait_TestCase;

		$actual = array_map( function( $value ) {
			return $value[0][1];
		}, $case->dataTestFunctions() );

		$expected = array(
			'private_trait_method',
			'protected_trait_method',
			'public_trait_method',
		);

		$this->assertEquals( $expected, $actual );
	}

}
