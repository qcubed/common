#!/usr/bin/env php
<?php
/**
 * This is a generic unit test runner. Options specify how it is run, and can be used for both
 * spot checks, and travis builds.
 */

/**
 * Define the working directory for the build. Tests should be run from the top level of
 * the repo we are testing.
 */
$workingDir = getcwd();

define('QCUBED_TEST_DIR', $workingDir);
$subdir = '';
if (isset ($argv[1])) {
	$subdir = '/' . $argv[1];
	define ('__SUBDIRECTORY__', $subdir);
	$_SERVER['argc']--; // prevents problems in codegen
	unset ($_SERVER['argc'][1]);
}

require( QCUBED_TEST_DIR . $subdir . '/test/travis-config.inc.php');
require (QCUBED_BASE_DIR . '/common/src/AutoLoader.php');
\QCubed\AutoLoader::init();
require_once(dirname(__FILE__) . '/UnitTestCaseBase.php');

// Codegen for testing
// Running as a Non-Windows Command Name
//$strCommandName = 'codegen.cli';
//define ('__CONFIGURATION__', __WORKING_DIR__ . $subdir . '/travis');

// Include the rest of the OS-agnostic script
//require( __DOCROOT__ . __SUBDIRECTORY__ . '/includes/_devtools/codegen.inc.php');


$cliOptions = [ 'phpunit'];	// first entry is the command
array_push($cliOptions, '-c', QCUBED_TEST_DIR . $subdir . '/test/phpunit.xml');	// the phpunit config file is here
//		array_push($cliOptions, '--bootstrap', __QCUBED_CORE__ . '/../vendor/autoload.php');

$tester = new PHPUnit_TextUI_Command();

return $tester->run($cliOptions);