<?php
namespace Johnbillion\DocsStandards\Tests;

class Docblock extends TestCase {

	/**
	 * Test that missing docblocks fail the test.
	 */
	public function testMissingDocblockFails() {

		require_once __DIR__ . '/includes/docblock-missing.php';

		$this->doFailTest(
			__NAMESPACE__ . '\docblock_missing',
			'testDocblockIsPresent',
			'Missing docblock passes the test',
			\Johnbillion\DocsStandards\TestCase::$docblock_missing
		);

	}

	/**
	 * Test that non-missing docblocks pass the test.
	 */
	public function testNonMissingDocblockPasses() {

		require_once __DIR__ . '/includes/docblock-missing.php';

		$this->doPassTest(
			__NAMESPACE__ . '\docblock_not_missing',
			'testDocblockIsPresent',
			'Non-missing docblock fails the test'
		);

	}

	/**
	 * Test that empty docblocks fail the test.
	 */
	public function testEmptyDocblockFails() {

		require_once __DIR__ . '/includes/docblock-empty.php';

		$this->doFailTest(
			__NAMESPACE__ . '\docblock_empty',
			'testDocblockHasDescription',
			'Empty docblock passes the test',
			\Johnbillion\DocsStandards\TestCase::$docblock_desc_empty
		);

	}

	/**
	 * Test that non-empty docblocks pass the test.
	 */
	public function testNonEmptyDocblockPasses() {

		require_once __DIR__ . '/includes/docblock-empty.php';

		$this->doPassTest(
			__NAMESPACE__ . '\docblock_not_empty',
			'testDocblockHasDescription',
			'Non-empty docblock fails the test'
		);

	}

}
