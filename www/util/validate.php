<?php

class Validate
{
	/** create new Validate object */
	function Validate() {
		//empty constructor
	}
	
	/** establishes if the msisdn is a valid Safaricom number
	* @param $msisdn the number to be validated - 10 digits with leading 0
	* @return true if valid; false otherwise
	*/
	function is_valid_msisdn($msisdn)
	{
		$continue = true;
		$match = false;
		
		//strip any leading and trailing whitespaces
		$msisdn = trim($msisdn);
		
		//first off establish we are dealing with a number
		if(!is_numeric($msisdn))
		{
			$continue = false;
			$msisdn = "";
		}
		
		//make sure the number has a valid prefix
		if($continue)
		{		
			if (strlen($msisdn) == 10) {
			$array_prefixes = array("07", "070", "071", "072");
			
			for($i=0; $i<count($array_prefixes); $i++)
			{
				$msisdn_prefix = substr($msisdn, 0, strlen($array_prefixes[$i]));
						
				if($msisdn_prefix == $array_prefixes[$i])
				{
					//since it has a valid prefix...
					if(strlen($msisdn) == 10)
					{
						$match = true;
						break;
					}
					/*
					//check for country code
					else if(strlen($msisdn_prefix) == 5 && strlen($msisdn) == 12)
					{
						$match = true;
						$msisdn = $msisdn;//$msisdn remains unchanged...
						break;
					} */				
				}
			}
			}
			else if (strlen($msisdn) == 9) {
			$array_prefixes = array("7", "70", "71", "72");
			
			for($i=0; $i<count($array_prefixes); $i++)
			{
				$msisdn_prefix = substr($msisdn, 0, strlen($array_prefixes[$i]));
						
				if($msisdn_prefix == $array_prefixes[$i])
				{
					//since it has a valid prefix...
					if(strlen($msisdn) == 9)
					{
						$match = true;
						break;
					}
					/*
					//check for country code
					else if(strlen($msisdn_prefix) == 5 && strlen($msisdn) == 12)
					{
						$match = true;
						$msisdn = $msisdn;//$msisdn remains unchanged...
						break;
					} */				
				}
			}
			}

			if(!$match) {
				$msisdn = "";
			}		
		}
		
		return $match;
	}
	
	/** establishes if the email has valid syntax
	* @param $email the address to be validated
	* @return true if valid; false otherwise
	*/
	function is_valid_email($email)
	{
	        if (!eregi("~^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$~", $email))
	        {
	                return false;
	    	}
	        else
	        {
	                return true;
	        }
	}
	
	/** establishes if the date is valid using the given format
	* @param $date_str the date to be validated
	* @param $date_format the format the $date_str is in <br>
	* If $date_format is null, uses default: mm-dd-yyyy <br>
	* N.B: Delimiters may be slash, dot, or hyphen
	* @return true if valid; false otherwise
	*/
	function is_valid_date($date_str, $date_format) {
		//checkdate format - mm, dd, yyyy
		if (!$date_format) {
			list($month, $day, $year) = split('[/.-]', $date_str);
			if (checkdate($month, $day, $year)) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			//under construction!!
		}
	}
	
	/** establishes if the date, with an alphabetic month, is valid using the given format
	* @param $date_str the date to be validated
	* @param $date_format the format the $date_str is in <br>
	* If $date_format is null, uses default: dd-mm-yyyy <br>
	* N.B: Delimiters may be slash, dot, or hyphen
	* @return true if valid; false otherwise
	*/
	function is_valid_alphabetic_date($date_str, $date_format) {
		$array_month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");		
		//checkdate format - mm, dd, yyyy
		if (!$date_format) {
			list($day, $month, $year) = split('[/.-]', $date_str);
			
			//convert alphabetic month to integer
			foreach ($array_month as $key => $value) {
				if ($month == $value)
					$month = $key;
			}
			
			if (checkdate($month, $day, $year)) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			//under construction!!
		}
	}
	
	/** establishes if an entry is only alphanumeric and contains acceptable characters such as space, underscore & dot
	* @param $value the variable to be checked
	* @return true if valid; false otherwise
	*/
	function is_valid_general($value) {
		$pattern = "~[/|A-Za-z0-9. _-]+~";
		$match = true;
		
		if (preg_match($pattern, $value)) {
			$value = preg_replace($pattern, '', $value);
		}
		else {
			$match = false;
		}			
		
		if ($match && $value == '') {
			return true;
		}
		else {
			return false;
		}
	}
	
	/** establishes if password given is good enough
	* requirements are: 6 - 10 characters, alphanumeric with atleast 1 cap & 1 small
	* @param $value the value to be checked
	* @return true if valid; false otherwise
	*/
	function is_good_password($value) {
		$array_validation = array ("~[A-Z]+~", "~[a-z]+~", "~[0-9]+~", "~[@._!£$%^&*+=-]*~");
		$match = true;
		
		foreach ($array_validation as $pattern) {		
			if (preg_match($pattern, $value)) {
				$value = preg_replace($pattern, '', $value);
			}
			else {
				$match = false;
				break;
			}			
		}
		
		if ($match && $value == '') {
			return true;
		}
		else {
			return false;
		}
	}

	/** checks that $to_date is equal to or greater than $from_date
	* @param $from_date the begin date
	* @param $to_date the end date
	* @return true if $from_date >= $to_date; false otherwise
	*/
	function date_verifier ($from_date, $to_date) {
		//convert to unix timestamp and compare
		$unix_from_date = strtotime($from_date);
		$unix_to_date = strtotime($to_date);
		
		if ($unix_to_date < $unix_from_date) {
			return false;
		}
		else {
			return true;
		}
	}

}
?>
