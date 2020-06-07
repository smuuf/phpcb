
█ <?= $tpl['app_name']; ?>, PHP <?= phpversion(); echo PHP_EOL; ?>

Engine used: <?= $tpl['engine_used']; ?>, Total time: <?= number_format($tpl['total_time'], 4); ?> sec, Iterations: <?= number_format($tpl['count'], 0, '.', ' '); ?>


<?php

	$position = 0;
	foreach($tpl['results'] as $index => $resultTime) {

		$closure = $tpl['closures'][$index];

		// Avoid division by zero in certain cases.
		if ($tpl['total_time'] && $resultTime && $tpl['min_time'] && $tpl['min_time'] - $tpl['max_time']) {

			$score = $tpl['min_time'] / $resultTime * 100;
			$score2 = $resultTime / $tpl['min_time'];

		} else {

			$score2 = false;
			$score = false;

		}

	echo sprintf('%s. Score: %s%s', $index + 1, $score !== false ? floor($score) : 'N/A', $score !== false ? '.' . sprintf('%02d', ($score - floor($score)) * 100) : null) ;
	echo ', ';

	echo number_format($resultTime, 4) . ' sec';

	if ($score2 != 1) {
		echo ', ';
		echo $score2 !== false ? number_format($score2, 2) . 'x slower' : 'N/A';
	}

	echo PHP_EOL;
	echo $tpl['source_function']($closure);
	echo PHP_EOL;

};
