<?php
namespace Johnbillion\DocsStandards\Tests;

class Params extends TestCase {

	/**
	 * Test that object classes in parameters are correctly detected.
	 */
	public function testParameterObjectClassIsDetected() {

		require_once __DIR__ . '/includes/param-object.php';

		$this->doPassTest(
			__NAMESPACE__ . '\param_object_class_exists',
			'testMethodParams',
			'Parameter object class not detected'
		);

		$this->doPassTest(
			__NAMESPACE__ . '\param_object_class_does_not_exist',
			'testMethodParams',
			'Parameter object class not detected'
		);

	}

}
