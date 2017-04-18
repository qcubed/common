<?php

/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed;

/**
 * Class AutoLoader
 *
 * The default autoloader for the QCubed framework.
 *
 * QCubed now uses PSR-4, so most files can be taken care of by the composer autoloader. However,
 * this autoloader is a 2nd level loader, in case the composer autoloader is not available. However, its slow,
 * so using composer's autoloader is preferable.
 *
 * Most paths are hard-coded, as there is no easy way to find these paths.
 *
 * @package QCubed
 */
class AutoLoader {
	protected static $blnInitialized = false;

	public static function init() {
		if (!self::$blnInitialized) {
			self::$blnInitialized = true;
			$strComposerAutoloadPath = dirname(QCUBED_BASE_DIR) . '/autoload.php';
			if (file_exists ($strComposerAutoloadPath)) {
				require ($strComposerAutoloadPath);
			}

			// Register the autoloader, making sure we go after the composer autoloader
			spl_autoload_register(array('QCubed\AutoLoader', 'autoload'), true, false);
		}
	}

	public static function autoload($strClassName) {
		if (strpos($strClassName, 'QCubed\\') === 0) {	// check common repo
			$strClassName = substr($strClassName, 7);
			$strPath = QCUBED_BASE_DIR . '/common/src/' . str_replace('\\', '/', $strClassName) . '.php';
			if (file_exists($strPath)) {
				require_once ($strPath);
				return true;
			}
		}

		return false;
	}
}
