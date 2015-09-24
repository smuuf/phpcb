<?php

namespace Smuuf\Phpcb;

class PhpBenchmark {

	const APP_NAME = 'phpcb';

	const MAX_COUNT = 1e6;
	const MIN_COUNT = 1;

	const BC_SCALE = 10;

	private $closures = array();
	private $sources = array();
	private $results = array();
	private $template = array();

	public function addBench(\Closure $callable) {
		$this->closures[] = $callable;
	}

	public function run($count = 10000){

		// Make sure the output of benchmarked code is not displayed.
		ob_start();

		// Set BC scale
		bcscale(self::BC_SCALE);

		// Limit the iterations count
		$count = min(max($count, self::MIN_COUNT), self::MAX_COUNT);

		// Measure call cost for a void closure
		$voidTime = self::measureVoidCallTime($count);

		// Run each of the benchmark closures
		foreach ($this->closures as $key => $function){

			$time = microtime(true);
			for ($i = 1; $i <= $count; $i++) $function();
			$this->results[$key] = microtime(true) - $time - $voidTime;

		}

		// Save the total count of iterations for the template
		$this->template['count'] = $count;

		// Render the output
		$this->renderResults();

	}

	private function renderResults(){

		ob_start();

		// Sort results from the best to the worst
		uasort($this->results, function($a, $b) {
			return $b < $a;
		});

		// Set up template variables
		$this->template['app_name'] = self::APP_NAME;
		$this->template['results'] = $this->results;
		$this->template['total_time'] = array_sum($this->results);
		$this->template['max_time'] = max($this->results);
		$this->template['min_time'] = min($this->results);
		$this->template['closures'] = $this->closures;
		$this->template['gradient_function'] = [$this, 'getGradientColor'];
		$this->template['percentage_function'] = [$this, 'getPercentage'];
		$this->template['source_function'] = [$this, 'getSourceCode'];

		// Render the template
		$tpl = $this->template;
		require __DIR__ . "/templates/results.php";

		ob_end_flush();

	}

	// Helpers

	protected static function measureVoidCallTime($count) {

		// Static cache; do this measurement only
		// once per request for any given $count.
		static $cache;
		if ($cache[$count]) return $cache[$count];

		$voidClosure = function() {};

		$time = microtime(true);
		for ($i = 1; $i <= $count; $i++) $voidClosure();
		$time = microtime(true) - $time;

		return $cache[$count] = $time;

	}

	// Template helpers

	private function getSourceCode(\Closure $closure) {

		$reflection = new \ReflectionFunction($closure);

		$file = $reflection->getFileName();
		$source = file($file, FILE_IGNORE_NEW_LINES);

		$startLine = $reflection->getStartLine();
		$endLine = $reflection->getEndLine() - 1;

		return implode(
			PHP_EOL,
			array_slice($source, $startLine, $endLine - $startLine)
		);

	}

	private function getGradientColor($percentage, $brightness = 200, $b = '00') {

		$green = $brightness * $percentage / 100 ;
		$red = $brightness * (1 - ($percentage / 100));

		$compensate = ($brightness - abs($green - $red)) / 2;
		$green += (int) $compensate;
		$red += (int) $compensate;

		$r = str_pad(dechex($red), 2, 0, STR_PAD_LEFT);
		$g = str_pad(dechex($green), 2, 0, STR_PAD_LEFT);

		return "#$r$g$b";

	}

}
