<?php

namespace Smuuf\Phpcb;

class ChaoticEngine implements IEngine {

	const BATCH_SIZE = 1000;

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
		while ($i <= $totalCount) {

			$whichKey = array_rand($remainingClosures);

			$y = min(self::BATCH_SIZE, $count);

			$time = microtime(true);
			while ($y--) {
-				$closures[$whichKey]();
			}

			$results[$whichKey] += microtime(true) - $time;

			if (++$closureCount[$whichKey] == $count) {
				unset($remainingClosures[$whichKey]);
			}

			$i += self::BATCH_SIZE;

		}

		return $results;

	}

}
