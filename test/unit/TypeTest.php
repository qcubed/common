<?php
/**
 * 
 * @package Tests
 */

use QCubed\Type;

class TypeTest extends UnitTestCaseBase {
	public function testCasting() {
		define('_FAIL_', 'fail');
		$cases = array( 
			//array(value, display, expected result, cast to type),
				array("25.0", "25.0", (float)25.0, Type::Float),
				array("25.1", "25.1", (float)25.1, Type::Float),
				array(25.0, "25.0", (float)25.0, Type::Float),
				array(25.1, "25.1", (float)25.1, Type::Float),
				array(true, "true", _FAIL_,Type::Float),
				array("true", "true", _FAIL_,Type::Float),
				array(false, "false", _FAIL_,Type::Float),
				array("false", "false", _FAIL_,Type::Float),
				array(1, "1", (float)1, Type::Float),
				array(0, "0", (float)0, Type::Float),
				array("1", "1", (float)1, Type::Float),
				array("0", "0", (float)0, Type::Float),
				array("25", "25", (float)25, Type::Float),
				array(25, "25", (float)25,Type::Float),
				array(34.51666666667, "34.51666666667", (float)34.51666666667,Type::Float),
				array(2147483648, "2147483648", (float)2147483648,Type::Float),
				array(-2147483648, "-2147483648", (float)-2147483648,Type::Float),
				array(-2147483649, "-2147483649", (float)-2147483649,Type::Float),
				array("34.51666666667", "34.51666666667", (float)34.51666666667,Type::Float),
				array("2147483648", "2147483648", (float)2147483648.0,Type::Float),
				array("-2147483648", "-2147483648", (float)-2147483648.0,Type::Float),
				array("-2147483649", "-2147483649", (float)-2147483649.0,Type::Float),

				array("25.0", "25.0", _FAIL_, Type::Integer),
				array("25.1", "25.1", _FAIL_, Type::Integer),
				array(25.0, "25.0", (int)25, Type::Integer),
				array(25.1, "25.1", _FAIL_, Type::Integer),
				array(true, "true", _FAIL_, Type::Integer),
				array("true", "true", _FAIL_, Type::Integer),
				array(false, "false", _FAIL_, Type::Integer),
				array("false", "false", _FAIL_, Type::Integer),
				array(1, "1", 1, Type::Integer),
				array(0, "0", 0, Type::Integer),
				array("1", "1", 1, Type::Integer),
				array("0", "0", 0, Type::Integer),
				array("25", "25", 25, Type::Integer),
				array(25, "25", 25, Type::Integer),
				array(34.51666666667, "34.51666666667", _FAIL_, Type::Integer),
				array(2147483648, "2147483648", 2147483648, Type::Integer),
				array(-2147483648, "-2147483648", (int)-2147483648, Type::Integer),
				array(-2147483649, "-2147483649", -2147483649, Type::Integer),
				array("34.51666666667", "34.51666666667", _FAIL_, Type::Integer),
				array("2147483648", "2147483648", 2147483648, Type::Integer),
				array("-2147483648", "-2147483648", (int)-2147483648, Type::Integer),
				array("-2147483649", "-2147483649", -2147483649, Type::Integer),

				//this number is never stored at full accuracy, so there's no way to tell if it should be
				// an int (perhaps we should force it if it can be?)
				array(1844674407370955161616,"1844674407370955161616",(double)1844674407370955161616, Type::Float), //"1844674407370955100000"
				array(1844674407370955161616,"1844674407370955161616","fail", Type::Integer), //"1844674407370955100000"

				//this one is
				array("1844674407370955161616","1844674407370955161616","1844674407370955161616", Type::Float),
				array("1844674407370955161616","1844674407370955161616","1844674407370955161616", Type::Integer),

				array(6, '6', '6', Type::String),
				array(6.94, '6.94', '6.94', Type::String),
				array(0.694*10, '6.94', '6.94', Type::String),
				);
		
		foreach($cases as $case)
		{
			$value = (string)$case[1].'('.gettype($case[0]).')';
			if($case[2] === _FAIL_)
			{
				$this->setExpectedException('QCubed\\Exception\\InvalidCast');
				Type::Cast($case[0], $case[3]);
				$this->setExpectedException(null);
			}
			else
			{
				$castValue = Type::Cast($case[0], $case[3]);
				$newValue = $castValue.'('.gettype($castValue).')';
				$this->assertTrue($castValue === $case[2], "$value cast as a ".$case[3]." is $newValue");
			}
		}
	}

}