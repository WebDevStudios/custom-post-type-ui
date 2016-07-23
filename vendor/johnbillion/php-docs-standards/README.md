[![License](https://img.shields.io/badge/license-GPL_v2%2B-blue.svg?style=flat-square)](http://opensource.org/licenses/GPL-2.0)
[![Build Status](https://img.shields.io/travis/johnbillion/php-docs-standards/master.svg?style=flat-square)](https://travis-ci.org/johnbillion/php-docs-standards)

# PHP Documentation Standards Tests

This abstract PHPUnit test case tests the standards and correctness of the inline documentation of your PHP functions and methods.

## What's tested?

 * The docblock should not be missing.
 * The docblock description should not be empty.
 * The number of `@param` docs should match the actual number of parameters.
 * The `@param` description for each parameter should not be empty.
 * The `@param` name for each parameter should be correct.
 * The `@param` type hint for each parameter should be correct.
 * The `@param` description for optional parameters should state that it is optional.
 * The `@param` description for required parameters should not state that it is optional.
 * The `@param` description for each parameter should state its default value, where appropriate.

Class-level docblocks are not yet tested.

## Installation

Add the package to your project's dev dependencies using Composer:

```bash
composer require johnbillion/php-docs-standards:~1.0 --dev
```

In your unit test bootstrap file, include the Composer autoloader. This will look something like this:

```php
require dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php';
```

## Usage

Add a new test class to your test suite that extends the docs standards test case. The two abstract methods that need to
be implemented are `getTestFunctions()` and `getTestClasses()`. These methods return an array of function names and
class names, respectively, which are to be run through the test suite to test their documentation standards.

In the current version of the test case, the functions and classes must be loaded (or available for autoloading) in the
current request. A future version of this test case will use static analysis in order to remove this requirement.

```php
<?php

class TestMyDocsStandards extends \Johnbillion\DocsStandards\TestCase {

	/**
	 * Return an array of function names that will be run through the test suite.
	 *
	 * @return array Function names to test.
	 */
	protected function getTestFunctions() {
		return array(
			'my_function_1',
			'my_function_2',
		);
	}

	/**
	 * Return an array of class names whose methods will be run through the test suite.
	 *
	 * @return array Class names to test.
	 */
	protected function getTestClasses() {
		return array(
			'My_Class_1',
			'My_Class_2',
		);
	}

}
```

## Why is this a unit test instead of a sniffer?

This was originally built to help the WordPress documentation team improve the documentation standards, and at the time
the fastest way for me to implement it was as a unit test. It could also be a sniffer, if someone wanted to convert it.

## License: GPLv2 or later ##

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
