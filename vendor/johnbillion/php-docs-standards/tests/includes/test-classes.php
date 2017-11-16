<?php
namespace Johnbillion\DocsStandards\Tests;

trait My_Trait {

	private function private_trait_method() {
		return 'Trait_Class::private_trait_method';
	}

	protected function protected_trait_method() {
		return 'Trait_Class::protected_trait_method';
	}

	public function public_trait_method() {
		return 'Trait_Class::public_trait_method';
	}

}

class My_Trait_TestCase extends Stub_TestCase {

	protected function getTestClasses() {
		return array(
			__NAMESPACE__ . '\My_Trait',
		);
	}

}
