<?php
namespace Savings;

/* formula: from \big(\frac{1+s}{1+i}\big)^{12(R-a)}S
			+ \sum_{j={12a}}^{12R} x\big(\frac{1+s}{1+i}\big)^{j-12a}
			= \sum_{j={12R}}^{12T} D(1+i)^{j} 
			to
			x = \frac{\sum_{j={12R}}^{12T} D(1+i)^\frac{j}{12}
			- \big(\frac{1+s}{1+i}\big)^{12(R-a)}S}
			{12(R-a) \sum_{j={12a}}^{12R} \big(\frac{1+s}{1+i}\big)^{\frac{j}{12}-a}} */
function getMonthlySavings($age_current, $age_retirement, $age_terminal,
		  $rate_inflation, $rate_interest, // yearly
		  $monthly_current, $monthly_desired, // income
		  $savings_current) {
	
	$real_rate = (1+$rate_interest) / (1+$rate_inflation); // yearly
	
	$desired_total = 0;
	for ($i = $age_retirement; $i <= $age_terminal; $i++) {
		// counting monthly inflation for simplification
		$desired_total += (12 * $monthly_desired * \pow(1 + $rate_inflation, $i - $age_retirement));
	}
	
	$savings_value_at_retirement = $savings_current * \pow($real_rate, $age_retirement - $age_current);
	
	$discounting = 0;
	for ($i = 12 * $age_current; $i < 12 * $age_retirement; $i++) {
		$discounting += \pow($real_rate, $i - 12 * $age_current);
	}
	$discounting *= 12*($age_retirement - $age_current);
	
	return (($desired_total - $savings_value_at_retirement) / $discounting);
}

function getYearlySavings($data) {
	// Missing value defaults
	// TODO: better mechanism for assigning default values
	if (!is_numeric($data['rate_inflation'])) $data['rate_inflation'] = 0.03;
	if (!is_numeric($data['rate_interest'])) $data['rate_interest'] = 0.015;
	
	// echo "<pre>";
	// 	\var_dump($data);
	\extract($data);
	$real_rate = (1+$rate_interest) / (1+$rate_inflation);
	
	$time_work = $age_retirement - $age_current;
	$time_retirement = $age_terminal - $age_retirement;
	
	$savings_at_retirement = $savings * \pow($real_rate, $time_work);
	// echo "Current savings value at retirement: ".$savings_at_retirement."\n";
	
	$dividend_adjusted = 0;
	for ($i = 1; $i <= $time_retirement; $i++) {
		$dividend_adjusted += $dividend * \pow(1 + $rate_inflation, $i);
	}
	// echo "Adjusted dividend sum: ".$dividend_adjusted."\n";
	
	$discounting = 0;
	for ($i = 1; $i < $time_work; $i++) {
		$discounting += \pow($real_rate, $i);
	}
	$discounting *= $time_work;
	// echo "Discounting: ".$discounting."\n";
	// echo "</pre>";
	return round(($dividend_adjusted - $savings_at_retirement) / $discounting, 2);
}
?>