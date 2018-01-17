<?php

// $Id: iCalendar_properties.php,v 1.13 2005/07/21 22:42:13 defacer Exp $

class IcalendarProperty
{

	// Properties can have parameters, but cannot have other properties or components

	public $parent_component = NULL;
	public $value = NULL;
	public $parameters = NULL;
	public $valid_parameters = NULL;
	// These are common for 95% of properties, so define them here and override as necessary
	public $val_multi = false;
	public $val_default = NULL;

	public function __construct()
	{
		$this->construct();
	}

	public function construct()
	{
		$this->parameters = [];
	}

	// If some property needs extra care with its parameters, override this
	// IMPORTANT: the parameter name MUST BE CAPITALIZED!
	public function isValidParameter($parameter, $value)
	{

		if (is_array($value)) {
			if (!IcalendarParameter::multipleValuesAllowed($parameter)) {
				return false;
			}
			foreach ($value as $item) {
				if (!IcalendarParameter::isValidValue($this, $parameter, $item)) {
					return false;
				}
			}
			return true;
		}

		return IcalendarParameter::isValidValue($this, $parameter, $value);
	}

	public function invariantHolds()
	{
		return true;
	}

	// If some property is very picky about its values, it should do the work itself
	// Only data type validation is done here
	public function isValidValue($value)
	{
		if (is_array($value)) {
			if (!$this->val_multi) {
				return false;
			} else {
				foreach ($value as $oneval) {
					if (!rfc2445_is_valid_value($oneval, $this->val_type)) {
						return false;
					}
				}
			}
			return true;
		}
		return rfc2445_is_valid_value($value, $this->val_type);
	}

	public function defaultValueICal()
	{
		return $this->val_default;
	}

	public function setParentComponent($componentname)
	{
		if (class_exists('Icalendar' . ucfirst(strtolower(substr($componentname, 1))))) {
			$this->parent_component = strtoupper($componentname);
			return true;
		}

		return false;
	}

	public function setValueICal($value)
	{
		if ($this->isValidValue($value)) {
			// This transparently formats any value type according to the iCalendar specs
			if (is_array($value)) {
				foreach ($value as $key => $item) {
					$value[$key] = rfc2445_do_value_formatting($item, $this->val_type);
				}
				$this->value = implode(',', $value);
			} else {
				$this->value = rfc2445_do_value_formatting($value, $this->val_type);
			}

			return true;
		}
		return false;
	}

	public function setParameterICal($name, $value)
	{

		// Uppercase
		$name = strtoupper($name);

		// Are we trying to add a valid parameter?
		if (!isset($this->valid_parameters[$name])) {
			// If not, is it an x-name as per RFC 2445?
			if (!rfc2445_is_xname($name)) {
				return false;
			}
			// No more checks -- all components are supposed to allow x-name parameters
		}

		if (!$this->isValidParameter($name, $value)) {
			return false;
		}

		if (is_array($value)) {
			foreach ($value as $key => $element) {
				$value[$key] = IcalendarParameter::doValueFormatting($name, $element);
			}
		} else {
			$value = IcalendarParameter::doValueFormatting($name, $value);
		}

		$this->parameters[$name] = $value;

		// Special case: if we just changed the VALUE parameter, reflect this
		// in the object's status so that it only accepts correct type values
		if ($name == 'VALUE') {
			$this->val_type = constant('RFC2445_TYPE_' . str_replace('-', '_', $value));
		}

		return true;
	}

	public function getParameterICal($name)
	{

		// Uppercase
		$name = strtoupper($name);

		if (isset($this->parameters[$name])) {
			// If there are any double quotes in the value, invisibly strip them
			if (is_array($this->parameters[$name])) {
				foreach ($this->parameters[$name] as $key => $value) {
					if (substr($value, 0, 1) == '"') {
						$this->parameters[$name][$key] = substr($value, 1, strlen($value) - 2);
					}
				}
				return $this->parameters[$name];
			} else {
				if (substr($this->parameters[$name], 0, 1) == '"') {
					return substr($this->parameters[$name], 1, strlen($this->parameters[$name]) - 2);
				}
			}
		}

		return NULL;
	}

	public function serialize()
	{
		$string = $this->name;

		if (!empty($this->parameters)) {
			foreach ($this->parameters as $name => $value) {
				$string .= ';' . $name . '=';
				if (is_array($value)) {
					$string .= implode(',', $value);
				} else {
					$string .= $value;
				}
			}
		}

		$string .= ':' . $this->value;

		return rfc2445_fold($string) . RFC2445_CRLF;
	}
}

class IcalendarPropertyCreated extends IcalendarProperty
{

	public $name = 'CREATED';
	public $val_type = RFC2445_TYPE_DATE_TIME;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}

	public function isValidValue($value)
	{
		if (!parent::isValidValue($value)) {
			return false;
		}
		// Time MUST be in UTC format
		return(substr($value, -1) == 'Z');
	}
}

class IcalendarPropertyDtstamp extends IcalendarProperty
{

	public $name = 'DTSTAMP';
	public $val_type = RFC2445_TYPE_DATE_TIME;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}

	public function isValidValue($value)
	{
		if (!parent::isValidValue($value)) {
			return false;
		}
		// Time MUST be in UTC format
		return(substr($value, -1) == 'Z');
	}
}

class IcalendarPropertyLastmodified extends IcalendarProperty
{

	public $name = 'LAST-MODIFIED';
	public $val_type = RFC2445_TYPE_DATE_TIME;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}

	public function isValidValue($value)
	{
		if (!parent::isValidValue($value)) {
			return false;
		}
		// Time MUST be in UTC format
		return(substr($value, -1) == 'Z');
	}
}

class IcalendarPropertySequence extends IcalendarProperty
{

	public $name = 'SEQUENCE';
	public $val_type = RFC2445_TYPE_INTEGER;
	public $val_default = 0;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}

	public function isValidValue($value)
	{
		if (!parent::isValidValue($value)) {
			return false;
		}
		$value = intval($value);
		return ($value >= 0);
	}
}

// 4.8.8 Miscellaneous Component Properties
// ----------------------------------------

class IcalendarPropertyX extends IcalendarProperty
{

	public $name = RFC2445_XNAME;
	public $val_type = NULL;

	public function construct()
	{
		$this->valid_parameters = [
			'LANGUAGE' => RFC2445_OPTIONAL | RFC2445_ONCE,
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}

	public function setName($name)
	{

		$name = strtoupper($name);

		if (rfc2445_is_xname($name)) {
			$this->name = $name;
			return true;
		}

		return false;
	}
}

class IcalendarPropertyRequeststatus extends IcalendarProperty
{

	// IMPORTANT NOTE: This property value includes TEXT fields
	// separated by semicolons. Unfortunately, auto-value-formatting
	// cannot be used in this case. As an exception, the value passed
	// to this property MUST be already escaped.

	public $name = 'REQUEST-STATUS';
	public $val_type = RFC2445_TYPE_TEXT;

	public function construct()
	{
		$this->valid_parameters = [
			'LANGUAGE' => RFC2445_OPTIONAL | RFC2445_ONCE,
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}

	public function isValidValue($value)
	{
		if (!is_string($value) || empty($value)) {
			return false;
		}

		$len = strlen($value);
		$parts = [];
		$from = 0;
		$escch = false;

		for ($i = 0; $i < $len; ++$i) {
			if ($value{$i} == ';' && !$escch) {
				// Token completed
				$parts[] = substr($value, $from, $i - $from);
				$from = $i + 1;
				continue;
			}
			$escch = ($value{$i} == '\\');
		}
		// Add one last token with the remaining text; if the value
		// ended with a ';' it was illegal, so check that this token
		// is not the empty string.
		$parts[] = substr($value, $from);

		$count = count($parts);

		// May have 2 or 3 tokens (last one is optional)
		if ($count != 2 && $count != 3) {
			return false;
		}

		// REMEMBER: if ANY part is empty, we have an illegal value
		// First token must be hierarchical numeric status (3 levels max)
		if (strlen($parts[0]) == 0) {
			return false;
		}

		if ($parts[0]{0} < '1' || $parts[0]{0} > '4') {
			return false;
		}

		$len = strlen($parts[0]);

		// Max 3 levels, and can't end with a period
		if ($len > 5 || $parts[0]{$len - 1} == '.') {
			return false;
		}

		for ($i = 1; $i < $len; ++$i) {
			if (($i & 1) == 1 && $parts[0]{$i} != '.') {
				// Even-indexed chars must be periods
				return false;
			} else if (($i & 1) == 0 && ($parts[0]{$i} < '0' || $parts[0]{$i} > '9')) {
				// Odd-indexed chars must be numbers
				return false;
			}
		}

		// Second and third tokens must be TEXT, and already escaped, so
		// they are not allowed to have UNESCAPED semicolons, commas, slashes,
		// or any newlines at all

		for ($i = 1; $i < $count; ++$i) {
			if (strpos($parts[$i], "\n") !== false) {
				return false;
			}

			$len = strlen($parts[$i]);
			if ($len == 0) {
				// Cannot be empty
				return false;
			}

			$parts[$i] .= '#'; // This guard token saves some conditionals in the loop

			for ($j = 0; $j < $len; ++$j) {
				$thischar = $parts[$i]{$j};
				$nextchar = $parts[$i]{$j + 1};
				if ($thischar == '\\') {
					// Next char must now be one of ";,\nN"
					if ($nextchar != ';' && $nextchar != ',' && $nextchar != '\\' &&
						$nextchar != 'n' && $nextchar != 'N') {
						return false;
					}

					// OK, this escaped sequence is correct, bypass next char
					++$j;
					continue;
				}
				if ($thischar == ';' || $thischar == ',' || $thischar == '\\') {
					// This wasn't escaped as it should
					return false;
				}
			}
		}

		return true;
	}

	public function setValueICal($value)
	{
		// Must override this, otherwise the value would be quoted again
		if ($this->isValidValue($value)) {
			$this->value = $value;
			return true;
		}

		return false;
	}
}

class IcalendarPropertyTrigger extends IcalendarProperty
{

	public $name = 'TRIGGER';
	public $val_type = RFC2445_TYPE_TEXT;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}
}

class IcalendarPropertyAction extends IcalendarProperty
{

	public $name = 'ACTION';
	public $val_type = RFC2445_TYPE_TEXT;

	public function construct()
	{
		$this->valid_parameters = [
			'DISPLAY' => RFC2445_OPTIONAL | RFC2445_ONCE,
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}
}

class IcalendarPropertyXwralarmuid extends IcalendarProperty
{

	public $name = 'X_WR_ALARMUID';
	public $val_type = RFC2445_TYPE_TEXT;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}
}

class IcalendarPropertyTzoffsetto extends IcalendarProperty
{

	public $name = 'TZOFFSETTO';
	public $val_type = RFC2445_TYPE_TEXT;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}
}

class IcalendarPropertyDaylightc extends IcalendarProperty
{

	public $name = 'DAYLIGHTC';
	public $val_type = RFC2445_TYPE_INTEGER;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}
}

class IcalendarPropertyStandardc extends IcalendarProperty
{

	public $name = 'STANDARDC';
	public $val_type = RFC2445_TYPE_INTEGER;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_OPTIONAL
		];
	}
}

class IcalendarPropertyTzid extends IcalendarProperty
{

	public $name = 'TZID';
	public $val_type = RFC2445_TYPE_TEXT;

	public function construct()
	{
		$this->valid_parameters = [
			RFC2445_XNAME => RFC2445_REQUIRED
		];
	}
}
