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
 * Class Translator
 *
 * Works together with a translator interface to implement I18N translation files.
 *
 * This is its own separate singleton file because translation is core to the entire framework, but this allows
 * the most independence from the rest of the framework. Implementing a translator is optional.
 *
 * @package QCubed
 */
class Translator {
	/**
	 * The singleton instance of the active QI18n object (which contains translation strings), if any.
	 * Must be defined during application startup if needed and implement the TranslatorInterface.
	 *
	 * @var TranslatorInterface $LanguageObject
	 */
	public static $LanguageObject;


	/**
	 * If LanguageCode is specified and QI18n::Initialize() has been called, then this
	 * will perform a translation of the given token for the specified Language Code and optional
	 * Country code.
	 *
	 * Otherwise, this will simply return the token as is.
	 * This method is also used by the global print-translated "_t" function.
	 *
	 * @static
	 * @param string $strToken
	 * @return string the Translated token (if applicable)
	 * @was QApplication::Translate
	 */
	public static function translate($strToken) {
		if (self::$LanguageObject)
			return self::$LanguageObject->translateToken($strToken);
		else
			return $strToken;
	}

	public static function _t($strToken) {
		return self::translate($strToken);
	}

}