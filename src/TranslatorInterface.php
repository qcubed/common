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
 * Extend this class to create different translation objects
 * @author Ago Luberg
 * @was QTranslationBase
 */
interface TranslatorInterface {
	/**
	 * Used to initialize translation
	 * Should return initiated translation object
	 * @abstract
	 * @return TranslatorInterface
	 */
	//static function initialize();

	/**
	 * Used to load translation instance
	 * @param string[optional] $strLanguageCode Language code
	 * @param string[optional] $strCountryCode Country code
	 * @return TranslatorInterface
	 * @abstract
	 */
	//static function load($strLanguageCode = null, $strCountryCode = null);

	/**
	 * Translates given token to given translation language
	 * @param string $strToken
	 * @return string
	 */
	function translateToken($strToken);
}
