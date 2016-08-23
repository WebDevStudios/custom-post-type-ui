<?php
namespace Johnbillion\DocsStandards;

abstract class TestCase extends \PHPUnit_Framework_TestCase {

	public static $docblock_missing                  = 'The docblock for `%s` should not be missing.';
	public static $docblock_desc_empty               = 'The docblock description for `%s` should not be empty.';
	public static $param_count_mismatch              = 'The number of @param docs for `%s` should match the actual number of parameters.';
	public static $param_desc_empty                  = 'The @param description for the `%s` parameter of `%s` should not be empty.';
	public static $param_name_incorrect              = 'The @param name for the `%s` parameter of `%s` is incorrect.';
	public static $param_type_hint_accept_array      = 'The @param type hint for the `%s` parameter of `%s` should state that it accepts an array.';
	public static $param_type_hint_accept_object     = 'The @param type hint for the `%s` parameter of `%s` should state that it accepts an object of type `%s`.';
	public static $param_type_hint_disallow_callback = '`callback` is not a valid type in the @param type hint for the `%s` parameter of `%s`. `callable` should be used instead.';
	public static $param_type_hint_accept_callable   = 'The @param type hint for the `%s` parameter of `%s` should state that it accepts a callable.';
	public static $param_type_hint_optional          = 'The @param description for the optional `%s` parameter of `%s` should state that it is optional.';
	public static $param_type_hint_not_optional      = 'The @param description for the required `%s` parameter of `%s` should not state that it is optional.';
	public static $param_type_hint_default           = 'The @param description for the `%s` parameter of `%s` should state its default value.';
	public static $param_type_hint_no_default        = 'The @param description for the `%s` parameter of `%s` should not state a default value.';

	protected $function_name = null;
	protected $docblock      = null;
	protected $doc_comment   = null;
	protected $method_params = null;
	protected $doc_params    = null;

	/**
	 * Return an array of function names that will be run through the test suite.
	 *
	 * @return array Function names to test.
	 */
	abstract protected function getTestFunctions();

	/**
	 * Return an array of class names whose methods will be run through the test suite.
	 *
	 * @return array Class names to test.
	 */
	abstract protected function getTestClasses();

	protected function setupFunction( $function ) {

		if ( is_array( $function ) ) {
			$ref  = new \ReflectionMethod( $function[0], $function[1] );
			$this->function_name = $function[0] . '::' . $function[1] . '()';
		} else {
			$ref  = new \ReflectionFunction( $function );
			$this->function_name = $function . '()';
		}

		$this->docblock      = new \phpDocumentor\Reflection\DocBlock( $ref );
		$this->doc_comment   = $ref->getDocComment();
		$this->method_params = $ref->getParameters();
		$this->doc_params    = $this->docblock->getTagsByName( 'param' );

	}

	/**
	 * Test for docblock presence for a function or method.
	 *
	 * @dataProvider dataTestFunctions
	 *
	 * @param string|array $function The function name, or array of class name and method name.
	 */
	public function testDocblockIsPresent( $function ) {

		$this->setupFunction( $function );

		$this->assertNotFalse( $this->doc_comment, sprintf(
			self::$docblock_missing,
			$this->function_name
		) );

	}

	/**
	 * Test for docblock description for a function or method.
	 *
	 * @dataProvider dataTestFunctions
	 *
	 * @param string|array $function The function name, or array of class name and method name.
	 */
	public function testDocblockHasDescription( $function ) {

		$this->setupFunction( $function );

		if ( ! $this->doc_comment ) {
			$this->markTestSkipped( 'Missing docblock' );
		}

		$this->assertNotEmpty( $this->docblock->getShortDescription(), sprintf(
			self::$docblock_desc_empty,
			$this->function_name
		) );

	}

	/**
	 * Test the docblock params list for a function or method.
	 *
	 * @dataProvider dataTestFunctions
	 *
	 * @param string|array $function The function name, or array of class name and method name.
	 */
	public function testDocblockParams( $function ) {

		$this->setupFunction( $function );

		if ( ! $this->docblock->getShortDescription() ) {
			$this->markTestSkipped( 'No docblock' );
		}

		$this->assertSame( count( $this->method_params ), count( $this->doc_params ), sprintf(
			self::$param_count_mismatch,
			$this->function_name
		) );

	}

	/**
	 * A static version of `ReflectionParameter::getName()` that doesn't require the parameter class to be loaded.
	 *
	 * This prevents fatal errors when a parameter uses a class name for type hinting but the class is not loaded.
	 *
	 * @param  \ReflectionParameter $param The parameter reflection object
	 * @return string                      The name of the parameter's type hinted object, if there is one.
	 */
	protected static function getParameterClassName( \ReflectionParameter $param ) {
		preg_match( '/\[\s\<\w+?>\s([a-zA-Z0-9_\\\\]+)/s', $param->__toString(), $matches );
		return isset( $matches[1] ) ? $matches[1] : null;
	}

	/**
	 * Test the params of a function or method.
	 *
	 * @dataProvider dataTestFunctions
	 *
	 * @param string|array $function The function name, or array of class name and method name.
	 */
	public function testMethodParams( $function ) {

		$this->setupFunction( $function );

		if ( ! $this->docblock->getShortDescription() ) {
			$this->markTestSkipped( 'No docblock' );
		}

		if ( empty( $this->method_params ) ) {
			$this->markTestSkipped( 'No method params to test' );
		}

		foreach ( $this->method_params as $i => $param ) {

			$param_doc   = $this->doc_params[ $i ];
			$description = $param_doc->getDescription();
			$content     = $param_doc->getContent();

			// @TODO decide how to handle variadic functions
			// ReflectionParameter::isVariadic — Checks if the parameter is variadic

			$is_hash = ( ( 0 === strpos( $description, '{' ) ) && ( ( strlen( $description ) - 1 ) === strrpos( $description, '}' ) ) );

			if ( $is_hash ) {
				$lines = explode( "\n", $description );
				$description = $lines[1];
			}

			$this->assertNotEmpty( $description, sprintf(
				self::$param_desc_empty,
				$param_doc->getVariableName(),
				$this->function_name
			) );

			list( $param_doc_type, $param_doc_name ) = preg_split( '#\s+#', $param_doc->getContent() );

			$this->assertSame( '$' . $param->getName(), $param_doc_name, sprintf(
				self::$param_name_incorrect,
				'$' . $param->getName(),
				$this->function_name
			) );

			if ( $param->isArray() ) {
				$this->assertNotFalse( strpos( $param_doc_type, 'array' ), sprintf(
					self::$param_type_hint_accept_array,
					$param_doc->getVariableName(),
					$this->function_name
				) );
			} elseif ( ( $param_class = self::getParameterClassName( $param ) ) && ( 'stdClass' !== $param_class ) ) {
				$namespaces  = explode( '\\', $param_class );
				$param_class = end( $namespaces );
				$this->assertNotFalse( strpos( $param_doc_type, $param_class ), sprintf(
					self::$param_type_hint_accept_object,
					$param_doc->getVariableName(),
					$this->function_name,
					$param_class
				) );
			}

			$this->assertFalse( strpos( $param_doc_type, 'callback' ), sprintf(
				self::$param_type_hint_disallow_callback,
				$param_doc->getVariableName(),
				$this->function_name
			) );

			if ( $param->isCallable() ) {
				$this->assertNotFalse( strpos( $param_doc_type, 'callable' ), sprintf(
					self::$param_type_hint_accept_callable,
					$param_doc->getVariableName(),
					$this->function_name
				) );
			}

			if ( $param->isOptional() ) {
				$this->assertNotFalse( strpos( $description, 'Optional.' ), sprintf(
					self::$param_type_hint_optional,
					$param_doc->getVariableName(),
					$this->function_name
				) );
			} else {
				$this->assertFalse( strpos( $description, 'Optional.' ), sprintf(
					self::$param_type_hint_not_optional,
					$param_doc->getVariableName(),
					$this->function_name
				) );
			}

			if ( $param->isDefaultValueAvailable() && ( array() !== $param->getDefaultValue() ) && ( null !== $param->getDefaultValue() ) ) {
				$this->assertNotFalse( strpos( $description, 'Default ' ), sprintf(
					self::$param_type_hint_default,
					$param_doc->getVariableName(),
					$this->function_name
				) );
			} else {
				$this->assertFalse( strpos( $description, 'Default ' ), sprintf(
					self::$param_type_hint_no_default,
					$param_doc->getVariableName(),
					$this->function_name
				) );
			}

		}

	}

	public function dataTestFunctions() {

		$data = array();

		foreach ( $this->getTestFunctions() as $function ) {

			if ( ! function_exists( $function ) ) {
				$this->fail( sprintf( 'Function `%s` does not exist.', $function ) );
			}

			$data[] = array(
				$function,
			);

		}

		foreach ( $this->getTestClasses() as $class ) {

			if ( ! class_exists( $class ) ) {
				$this->fail( sprintf( 'Class `%s` does not exist.', $class ) );
			}

			$class_ref = new \ReflectionClass( $class );

			foreach ( $class_ref->getMethods() as $method_ref ) {

				if ( $method_ref->class !== $class ) {
					continue;
				}

				$data[] = array(
					array(
						$class,
						$method_ref->getName(),
					),
				);

			}

		}

		return $data;

	}

}
