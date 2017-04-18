<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed;

use QCubed\Exception\Caller;

/**
 * Class DateTimeSpan: This class is used to calculate the time difference between two dates (including time)
 *
 * @property-read int $Years   Years in the calculated timespan
 * @property-read int $Months  Months in the calculated timespan
 * @property-read int $Days    Days in the calculated timespan
 * @property-read int $Hours   Hours in the calculated timespan
 * @property-read int $Minutes Minutes in the calculated timespan
 * @property int      $Seconds Number seconds which correspond to the time difference
 * @was QDateTimeSpan
 */
class DateTimeSpan extends AbstractBase {
	/** @var int Seconds variable which will be used to calculate the timespan */
	protected $intSeconds;

	/* From: http://tycho.usno.navy.mil/leapsec.html:
		This definition was ratified by the Eleventh General Conference on Weights and Measures in 1960.
		Reference to the year 1900  does not mean that this is the epoch of a mean solar day of 86,400 seconds.
		Rather, it is the epoch of the tropical year of 31,556,925.9747 seconds of ephemeris time.
		Ephemeris Time (ET) was defined as the measure of time that brings the observed positions of the celestial
		bodies into accord with the Newtonian dynamical theory of motion.
	*/
	/** Number of seconds in an year */
	const SecondsPerYear	= 31556926;
	
	// Assume 30 Days per Month
	/** Number of seconds in a month (assuming 30 days in a month) */
	const SecondsPerMonth 	= 2592000;
	/** Number of seconds per day */
	const SecondsPerDay 	= 86400;
	/** Number of seconds in an hour */
	const SecondsPerHour 	= 3600;
	/** Number of seconds per minute */
	const SecondsPerMinute 	= 60;

	/**
	 * Constructor for the DateTimeSpan class
	 *
	 * @param int $intSeconds Number of seconds to set for this DateTimeSpan
	 */
	public function __construct($intSeconds = 0) {
		$this->intSeconds = $intSeconds;
	}

	/*
		Is functions
	*/ 
	
	/**
	 * Checks if the current DateSpan is positive
	 *
	 * @return boolean
	 */
	public function IsPositive(){
		return ($this->intSeconds > 0);
	}

	/**
	 * Checks if the current DateSpan is negative
	 *
	 * @return boolean
	 */
	public function IsNegative(){
		return ($this->intSeconds < 0);
	}

	/**
	 * Checks if the current DateSpan is zero
	 *
	 * @return boolean
	 */
	public function IsZero(){
		return ($this->intSeconds == 0);
	}
	
	/**
	 * Calculates the difference between this DateSpan and another DateSpan
	 *
	 * @param DateTimeSpan $dtsSpan
	 * @return DateTimeSpan
	 */
	public function Difference(DateTimeSpan $dtsSpan){
		$intDifference = $this->Seconds - $dtsSpan->Seconds;
		$dtsDateSpan = new DateTimeSpan();
		$dtsDateSpan->AddSeconds($intDifference);
		return $dtsDateSpan;
	}
	
	/*
		SetFrom methods
	*/
	
	/**
	 * Sets current DateTimeSpan to the difference between two DateTime objects
	 *
	 * @param DateTime $dttFrom
	 * @param DateTime $dttTo
	 */
	public function SetFromQDateTime(DateTime $dttFrom, DateTime $dttTo){
		$this->Add($dttFrom->Difference($dttTo));
	}
	
	/*
		Add methods
	*/	
	
	/**
	 * Adds an amount of seconds to the current DateTimeSpan
	 *
	 * @param int $intSeconds
	 */
	public function AddSeconds($intSeconds){
		$this->intSeconds = $this->intSeconds + $intSeconds;
	}
	
	/**
	 * Adds an amount of minutes to the current DateTimeSpan
	 *
	 * @param int $intMinutes
	 */
	public function AddMinutes($intMinutes){
		$this->intSeconds = $this->intSeconds + ($intMinutes * DateTimeSpan::SecondsPerMinute);
	}
	
	/**
	 * Adds an amount of hours to the current DateTimeSpan
	 *
	 * @param int $intHours
	 */
	public function AddHours($intHours){
		$this->intSeconds = $this->intSeconds + ($intHours * DateTimeSpan::SecondsPerHour);
	}
	
	/**
	 * Adds an amount of days to the current DateTimeSpan
	 *
	 * @param int $intDays
	 */
	public function AddDays($intDays){
		$this->intSeconds = $this->intSeconds + ($intDays * DateTimeSpan::SecondsPerDay);
	}
	
	/**
	 * Adds an amount of months to the current DateTimeSpan
	 *
	 * @param int $intMonths
	 */
	public function AddMonths($intMonths){
		$this->intSeconds = $this->intSeconds + ($intMonths * DateTimeSpan::SecondsPerMonth);
	}
	
	/* 
		Get methods
	*/
	
	/**
	 * Calculates the total whole years in the current DateTimeSpan
	 *
	 * @return int
	 */
	protected function GetYears() {
		$intSecondsPerYear = ($this->IsPositive()) ? DateTimeSpan::SecondsPerYear : ((-1) * DateTimeSpan::SecondsPerYear);
		$intYears = floor($this->intSeconds / $intSecondsPerYear);
		if ($this->IsNegative()) $intYears = (-1) * $intYears;
		return $intYears;
	}

	/**
	 * Calculates the total whole months in the current DateTimeSpan
	 *
	 * @return int
	 */
	protected function GetMonths(){
		$intSecondsPerMonth = ($this->IsPositive()) ? DateTimeSpan::SecondsPerMonth : ((-1) * DateTimeSpan::SecondsPerMonth);
		$intMonths = floor($this->intSeconds / $intSecondsPerMonth);
		if($this->IsNegative()) $intMonths = (-1) * $intMonths;
		return $intMonths;
	}
	
	/**
	 * Calculates the total whole days in the current DateTimeSpan
	 *
	 * @return int
	 */
	protected function GetDays(){
		$intSecondsPerDay = ($this->IsPositive()) ? DateTimeSpan::SecondsPerDay : ((-1) * DateTimeSpan::SecondsPerDay);
		$intDays = floor($this->intSeconds / $intSecondsPerDay);
		if($this->IsNegative()) $intDays = (-1) * $intDays;
		return $intDays;
	}

	/**
	 * Calculates the total whole hours in the current DateTimeSpan
	 *
	 * @return int
	 */
	protected function GetHours(){
		$intSecondsPerHour = ($this->IsPositive()) ? DateTimeSpan::SecondsPerHour : ((-1) * DateTimeSpan::SecondsPerHour);
		$intHours = floor($this->intSeconds / $intSecondsPerHour);
		if($this->IsNegative()) $intHours = (-1) * $intHours;
		return $intHours;
	}
	
	/**
	 * Calculates the total whole minutes in the current DateTimeSpan
	 *
	 * @return int
	 */
	protected function GetMinutes(){
		$intSecondsPerMinute = ($this->IsPositive()) ? DateTimeSpan::SecondsPerMinute : ((-1) * DateTimeSpan::SecondsPerMinute);
		$intMinutes = floor($this->intSeconds / $intSecondsPerMinute);
		if($this->IsNegative()) $intMinutes = (-1) * $intMinutes;
		return $intMinutes;
	} 
	
	/*
		DateMathSettings
	*/
	
	/**
	 * Adds a DateTimeSpan to current DateTimeSpan
	 *
	 * @param DateTimeSpan $dtsSpan
	 */
	public function Add(DateTimeSpan $dtsSpan){
		$this->intSeconds = $this->intSeconds + $dtsSpan->Seconds;
	}
	
	/**
	 * Subtracts a DateTimeSpan to current DateTimeSpan
	 *
	 * @param DateTimeSpan $dtsSpan
	 */
	public function Subtract(DateTimeSpan $dtsSpan){
		$this->intSeconds = $this->intSeconds - $dtsSpan->Seconds;
	}

	/**
	 * Returns the time difference in approximate duration
	 * e.g. "about 4 months" or "4 minutes"
	 *
	 * The DateTime class uses this function in its 'Age' property accessor
	 *
	 * @return null|string
	 */
	public function SimpleDisplay(){
		$arrTimearray = $this->GetTimearray();
		$strToReturn = null;

		if($arrTimearray['Years'] != 0) {
			$strFormat = ($arrTimearray['Years'] != 1) ? Translator::translate('about %s years') :  Translator::translate('a year');
			$strToReturn = sprintf($strFormat, $arrTimearray['Years']);
		}
		elseif($arrTimearray['Months'] != 0){
			$strFormat = ($arrTimearray['Months'] != 1) ? Translator::translate('about %s months') : Translator::translate('a month');
			$strToReturn = sprintf($strFormat,$arrTimearray['Months']);
		}
		elseif($arrTimearray['Days'] != 0){
			$strFormat = ($arrTimearray['Days'] != 1) ? Translator::translate('about %s days') : Translator::translate('a day');
			$strToReturn = sprintf($strFormat,$arrTimearray['Days']);
		}
		elseif($arrTimearray['Hours'] != 0){
			$strFormat = ($arrTimearray['Hours'] != 1) ? Translator::translate('about %s hours') : Translator::translate('an hour');
			$strToReturn = sprintf($strFormat,$arrTimearray['Hours']);
		}
		elseif($arrTimearray['Minutes'] != 0){
			$strFormat = ($arrTimearray['Minutes'] != 1) ? Translator::translate('%s minutes') : Translator::translate('a minute');
			$strToReturn = sprintf($strFormat,$arrTimearray['Minutes']);
		}
		elseif($arrTimearray['Seconds'] != 0 ){
			$strFormat = ($arrTimearray['Seconds'] != 1) ? Translator::translate('%s seconds') : Translator::translate('a second');
			$strToReturn = sprintf($strFormat,$arrTimearray['Seconds']);
		}
		
		return $strToReturn;
	}
	
	
	/**
	 * Return an array of timeunints
	 *
	 * @return array of timeunits
	 */
	protected function GetTimearray(){
		$intSeconds = abs($this->intSeconds);

		$intYears = floor($intSeconds / DateTimeSpan::SecondsPerYear);
		$intSeconds = $intSeconds - ($intYears * DateTimeSpan::SecondsPerYear);

		$intMonths = floor($intSeconds / DateTimeSpan::SecondsPerMonth);
		$intSeconds = $intSeconds - ($intMonths * DateTimeSpan::SecondsPerMonth);

		$intDays = floor($intSeconds / DateTimeSpan::SecondsPerDay);
		$intSeconds = $intSeconds - ($intDays * DateTimeSpan::SecondsPerDay);
		
		$intHours = floor($intSeconds / DateTimeSpan::SecondsPerHour);
		$intSeconds = $intSeconds - ($intHours * DateTimeSpan::SecondsPerHour);
		
		$intMinutes = floor($intSeconds / DateTimeSpan::SecondsPerMinute);
		$intSeconds = $intSeconds - ($intMinutes * DateTimeSpan::SecondsPerMinute);

		if($this->IsNegative()){
			// Turn values to negative
			$intYears = ((-1) * $intYears);
			$intMonths = ((-1) * $intMonths);
			$intDays = ((-1) * $intDays);
			$intHours = ((-1) * $intHours);
			$intMinutes = ((-1) * $intMinutes);
			$intSeconds = ((-1) * $intSeconds);
		}

		return array('Years' => $intYears, 'Months' => $intMonths, 'Days' => $intDays, 'Hours' => $intHours, 'Minutes' => $intMinutes,'Seconds' => $intSeconds);
	}

	/**
	 * Override method to perform a property "Get"
	 * This will get the value of $strName
	 * PHP magic method
	 *
	 * @param string $strName Name of the property to get
	 *
	 * @return mixed the returned property
	 * @throws \Exception|Caller
	 */
	
	public function __get($strName) {
		switch ($strName) {
			case 'Years': return $this->GetYears();
			case 'Months': return $this->GetMonths();
			case 'Days': return $this->GetDays();
			case 'Hours': return $this->GetHours();
			case 'Minutes': return $this->GetMinutes();
			case 'Seconds': return $this->intSeconds;
			case 'Timearray' : return ($this->GetTimearray());

			default:
				try {
					return parent::__get($strName);
				} catch (Caller $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

	/**
	 * Override method to perform a property "Set"
	 * This will set the property $strName to be $mixValue
	 * PHP magic method
	 *
	 * @param string $strName  Name of the property to set
	 * @param string $mixValue New value of the property
	 *
	 * @return mixed the property that was set
	 * @throws \Exception|Caller
	 */

	public function __set($strName, $mixValue) {
		try {
			switch ($strName) {
				case 'Seconds':
					return ($this->intSeconds = Type::Cast($mixValue, Type::Integer));
				default:
					return (parent::__set($strName, $mixValue));
			}				
		} catch (Caller $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}
	}
}