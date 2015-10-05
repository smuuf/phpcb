<?php

namespace Smuuf\Phpcb;

class SerialEngine implements IEngine {

	public function getEngineName() {
		return 'Serial Engine';
	}

	public function run($count, array $closures) {

		$results = array();

		// Measure call cost for a void closure
		$voidTime = self::measureVoidCallTime($count);

		// Run each of the benchmark closures
		foreach ($closures as $key => $function){

			$time = microtime(true);
			for ($i = $count; $i--;) $function();
			$results[$key] = microtime(true) - $time - $voidTime;

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
