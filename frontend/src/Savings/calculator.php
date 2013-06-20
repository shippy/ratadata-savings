<?php
namespace Savings;

class Calculator {
	static $rate_inflation, $rate_interest;
	
	function __construct($data) {
		$this->update($data);
	}
	
	public static function update($data) {
		foreach ($data as $key => $value) {
			# code...
		}
	}
	
	public static function getYearlySavings($data) {
		// Missing value defaults
		// TODO: better mechanism for assigning default values
		if (!isset($data['rate_inflation']) || !is_numeric($data['rate_inflation'])) $data['rate_inflation'] = 0.03;
		if (!isset($data['rate_interest']) || !is_numeric($data['rate_interest'])) $data['rate_interest'] = 0.015;

		// echo "<pre>";
		// 	\var_dump($data);
		\extract($data);
		$real_rate = (1+$rate_interest) / (1+$rate_inflation);

		$time_work = $age_retirement - $age_current;
		$time_retirement = $age_terminal - $age_retirement;

		$savings_at_retirement = $savings * \pow($real_rate, $time_work);
		// echo "Current savings value at retirement: ".$savings_at_retirement."\n";
		
		// Desired dividend in current money; adjust by real interest rate to preserve value
		$dividend_real = $dividend*\pow(1+$rate_inflation, $time_work);
		// echo "Dividend $dividend (2013 CZK) converted to ".round($dividend_real, 2)." (".(2013+$time_work)." CZK)";
		
		$dividend_adjusted = 0;
		for ($i = 1; $i <= $time_retirement; $i++) {
			$dividend_adjusted += $dividend_real * \pow(1 + $rate_inflation, $i);
		}
		// echo "Adjusted dividend sum: ".$dividend_adjusted."\n";

		$discounting = 0;
		for ($i = 1; $i <= $time_work; $i++) {
			$discounting += \pow($real_rate, $i);
		}
		$discounting *= $time_work;
		// echo "Discounting: ".$discounting."\n";
		// echo "</pre>";
		return round(($dividend_adjusted - $savings_at_retirement) / $discounting, 2);
	}
	
	public function getRealRate() {
		return (1+$rate_interest) / (1+$rate_inflation);
	}
}
?>