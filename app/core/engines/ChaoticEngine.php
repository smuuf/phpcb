<?php

namespace Smuuf\Phpcb;

class ChaoticEngine implements IEngine {

	public function getEngineName() {
		return 'Chaotic Engine';
	}

	public function run($count, array $closures) {

		$totalCount = count($closures) * $count;

		$results = array();
		$remainingClosures = array();
		$closureCount = array();
		foreach ($closures as $closure) {

			$results[] = 0;
			$remainingClosures[] = true;
			$closureCount[] = 0;

		}

		$i = 0;
		while ($i++ - $totalCount) {

			$whichKey = array_rand($remainingClosures);

			$time = microtime(true);
			$closures[$whichKey]();
			$results[$whichKey] += microtime(true) - $time;

			if (++$closureCount[$whichKey] == $count) {
				unset($remainingClosures[$whichKey]);
			}

		}

		return $results;

	}

}
