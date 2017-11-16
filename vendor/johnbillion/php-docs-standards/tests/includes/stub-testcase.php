<?php
namespace Johnbillion\DocsStandards\Tests;

class Stub_TestCase extends \Johnbillion\DocsStandards\TestCase {

	public function setUp() {
		$this->markTestSkipped( 'This is a stub test class.' );
	}

	protected function getTestFunctions() {
		return array();
	}

	protected function getTestClasses() {
		return array();
	}

}
