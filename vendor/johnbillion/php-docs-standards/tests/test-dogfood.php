<?php
namespace Johnbillion\DocsStandards\Tests;

foreach ( glob( __DIR__ . '/test-*.php' ) as $file ) {
	require_once $file;
}

/**
 * Test the tests that test the tests.
 */
class Dogfood extends \Johnbillion\DocsStandards\TestCase {

	/**
	 * Return an array of function names that will be run through the test suite.
	 *
	 * @return array Function names to test.
	 */
	protected function getTestFunctions() {
		return array();
	}

	/**
	 * Return an array of class names whose methods will be run through the test suite.
	 *
	 * @return array Class names to test.
	 */
	protected function getTestClasses() {
		return array(
			'\Johnbillion\DocsStandards\TestCase',
			'\Johnbillion\DocsStandards\Tests\TestCase',
			__NAMESPACE__ . '\Docblock',
			__NAMESPACE__ . '\Params',
			__NAMESPACE__ . '\Dogfood',
		);
	}

}
