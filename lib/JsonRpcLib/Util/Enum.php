<?php
/**
 * (The MIT License)
 * 
 * Copyright (c) 2011 Sean Hickey headz@headzoo.org
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the 'Software'), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included 
 * in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * https://github.com/headzoo/php-enum
 * 
 */

namespace JsonRpcLib\Util;

/**
 * Represents a enumeration
 *
 * Inherited from sub-classes to create enum-like objects. Functions can
 * then type hint to require a certain enum value. All child classes must
 * have a __default constant, and one or more constants representing the 
 * enum values. The values of the constants must be integers.
 *
 * @version 0.3
 */
abstract class Enum
{
	/**
	 * The current enum value
	 */
	private $__value = null;
	
	/**
	 * All the values of the enum constants
	 */
	private $__constants = null;
	
	/**
	 * Constructor
	 *
	 * @param string|int $value The enum value
	 */
	public function __construct($value = null)
	{
		// Get all the class constants. They *must* be integer values so this
		// enum class behaves like traditional enums.
		if ($this->__constants === null) {
			$ref_class = new \ReflectionClass($this);
			$this->__constants = $ref_class->getConstants();
			foreach($this->__constants as $constant => $const_value) {
				if (!is_int($const_value)) {
					throw new \RuntimeException("Enum constant values must be integers");
				}
			}
			
			// All derived classes must include a __default constant
			if (!isset($this->__constants['__default'])) {
				throw new \Exception("Class constant __default does not exist");
			}
		}

		// If $value is a string, check if it's a valid enum constant, and set the value
		// to the constant value.
		$this->__value = $value ?: $this->__constants['__default'];
		if (is_string($this->__value)) {
			if (!$const_key = $this->getArrayKey($this->__value, $this->__constants)) {
				throw new \UnexpectedValueException("The value '$value' is not one of the enum constants");
			}
			$this->__value = $this->__constants[$const_key];
		} else if (!is_int($this->__value)) {
			throw new \InvalidArgumentException("The value must be a string or integer");
		}
	}
	
	/**
	 * Returns all the enum constant values as an array
	 *
	 * @param bool $incldue_default Whether to include the value of __default
	 * @return array
	 */
	public function getConstList($include_default = false)
	{
		if (!$include_default) {
			$temp = $this->__constants;
			unset($temp['__default']);
			return $temp;
		}
		return $this->__constants;
	}
	
	/**
	 * Returns the current integer value of the enum
	 *
	 * @see http://www.php.net/manual/en/language.oop5.magic.php#language.oop5.magic.invoke
	 * @return int
	 */
	public function __invoke()
	{
		return $this->__value;
	}
	
	/**
	 * Static method for creating an instance of the enum
	 *
	 * A factory method for creating a new instance of the inherited enum. The value of
	 * the enum will be the static method name.
	 *
	 * @see http://www.php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
	 * @return Enum
	 */
	public static function __callStatic($value, $args)
	{
		return new static($value);
	}
	
	/**
	 * Returns the current string value of the enum
	 *
	 * @see http://www.php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
	 * @return string
	 */
	public function __toString()
	{
		return (string)array_search($this->__value, $this->getConstList(false));
	}
	
	/**
	 * Searches $arr for the given key, and returns the found key
	 *
	 * This is a case-insensitive search. In a case-insensitive manner, the array is search
	 * for $key, and returns the found key (With proper case), or false if the key isn't
	 * found.
	 *
	 * @param string $key The key to search for
	 * @param array $arr The array to search
	 * @return mixed
	 */
	private function getArrayKey($key, $arr)
	{
		$key = strtolower($key);
		foreach($arr as $arrkey => $value) {
			$checkkey = strtolower($arrkey);
			if ($key == $checkkey) {
				return $arrkey;
			}
		}
		return false;
	}
}
