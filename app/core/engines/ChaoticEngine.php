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

	// Helpers

	protected static function measureVoidCallTime($count) {

		// Static cache; do this measurement only
		// once per request for any given $count.
		static $cache;
		if ($cache[$count]) return $cache[$count];

		$voidClosure = function() {};

		$time = microtime(true);
		for ($i = $count; $i--;) $voidClosure();
		$time = microtime(true) - $time;

		return $cache[$count] = $time;

	}

}
