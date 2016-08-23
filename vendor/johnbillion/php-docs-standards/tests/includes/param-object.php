<?php
namespace Johnbillion\DocsStandards\Tests;

class Param_Object_Class {}

/**
 * Description
 *
 * @param Param_Object_Class $object An object
 */
function param_object_class_exists( Param_Object_Class $object ) {}

/**
 * Description
 *
 * @param Param_Object_Class_Missing|false $object An object
 */
function param_object_class_does_not_exist( Param_Object_Class_Missing $object ) {}
