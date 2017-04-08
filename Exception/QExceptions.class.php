<?php


	/**
	 * Used when trying to access a table object which does not have a primary key defined on it
	 */
	class QUndefinedPrimaryKeyException extends QCallerException {
		/**
		 * Constructor method
		 * @param string $strMessage
		 */
		public function __construct($strMessage) {
			parent::__construct($strMessage, 2);
		}
	}




	/**
	 * Thrown when optimistic locking (in ORM Save() method) detects that DB data was updated
	 */
	class QOptimisticLockingException extends QCallerException {
		/**
		 * Constructor method
		 * @param string $strClass
		 */
		public function __construct($strClass) {
			parent::__construct(sprintf(QApplication::Translate('Optimistic Locking constraint when trying to update %s object.  To update anyway, call ->Save() with $blnForceUpdate set to true'), $strClass, 2));
		}
	}

	/**
	 * Thrown when the desired page is protected by ALLOW REMOTE ADMIN feature and the request does not qualify
	 */
	class QRemoteAdminDeniedException extends QCallerException {
		/**
		 * Constructor method
		 */
		public function __construct() {
			parent::__construct(
				sprintf(
					QApplication::Translate('Remote access to "%s" has been disabled.' . "\n" .
					'To allow remote access to this script, set the ALLOW_REMOTE_ADMIN constant to TRUE' . "\n" .
					'or to "%s" in "configuration.inc.php".')
					, QApplication::$RequestUri, $_SERVER['REMOTE_ADDR'])
				, 2);
		}
	}

	/**
	 * Thrown when formstate is not found
	 */
	class QInvalidFormStateException extends QCallerException {
		/**
		 * Constructor method
		 * @param string $strFormId Form ID for which the state was not found
		 */
		public function __construct($strFormId) {
			parent::__construct(sprintf(QApplication::Translate('Invalid Form State Data for "%s" object (session may have been lost)'), $strFormId), 2);
		}
	}

	/**
	 * @property-read integer $Offset
	 * @property-read mixed $BackTrace
	 * @property-read string $Query
	 */
	class QDataBindException extends Exception {
		private $intOffset;
		private $strTraceArray;
		private $strQuery;

		public function __construct(QCallerException $objExc) {
			parent::__construct($objExc->getMessage(), $objExc->getCode());
			$this->intOffset = $objExc->Offset;
			$this->strTraceArray = $objExc->TraceArray;

			if ($objExc instanceof QDatabaseExceptionBase)
				$this->strQuery = $objExc->Query;

			$this->file = $this->strTraceArray[$this->intOffset]['file'];
			$this->line = $this->strTraceArray[$this->intOffset]['line'];
		}

		public function __get($strName) {
			switch($strName) {
				case "Offset":
					return $this->intOffset;

				case "BackTrace":
					$objTraceArray = debug_backtrace();
					return (var_export($objTraceArray, true));

				case "Query":
					return $this->strQuery;
			}
		}
	}