<?php

namespace Smuuf\Phpcb;

class ChaoticEngine implements IEngine {

	public function getEngineName() {
		return 'Chaotic Engine';
	}

	public function run($count, array $closures) {

		// Build the "plan" array containing all indexes of closures we're
		// going to be measuring.
		// For example:
		//   if count($closures) = 3
		//   then $plan == [0, 1, 2]
		$plan = range(0, count($closures) - 1);

		// Prepare zero-filled array for adding time results.
		$times = array_fill(0, count($closures), 0);

		// Prepare result-checking.
		$lastResult = null;
		$first = true;

		for ($i = 0; $i < $count; $i++) {
			foreach ($plan as $key) {

				$time = microtime(true);
				$result = $closures[$key]();
				$times[$key] += microtime(true) - $time;

				if (!$first && $lastResult !== $result) {
					throw new DifferentResultException(
						"Closure #$key returned different result.",
						$result,
						$lastResult
					);
				}

				$first = false;
				$lastResult = $result;

			}
		}

		return $times;

	}

}
