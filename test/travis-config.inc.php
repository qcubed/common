<?php

/**
 * The base configuration file for the travis test
 */
define ('__DOCROOT__', dirname(dirname(dirname(QCUBED_TEST_DIR))));
define ('__VIRTUAL_DIRECTORY__', '');
if (!defined ('__SUBDIRECTORY__')) {
	define ('__SUBDIRECTORY__', '');
}

define('__APPLICATION_ENCODING_TYPE__', 'UTF-8');

define ('QCUBED_BASE_DIR',  __DOCROOT__ . __SUBDIRECTORY__ . '/vendor/qcubed');
