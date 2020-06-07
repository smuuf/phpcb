<?php

namespace Smuuf\Phpcb;

class PhpBenchmark {

	const APP_NAME = 'phpcb';
	const GITHUB_LINK = 'http://github.com/smuuf/phpcb/';

	const DEFAULT_ENGINE = '\Smuuf\Phpcb\ChaoticEngine';

	const MAX_COUNT = PHP_INT_MAX;
	const MIN_COUNT = 1;

	const BC_SCALE = 10;

	private $closures = [];
	private $results = [];
	private $template = [];
	private $templateType;

	public function __construct(IEngine $engine = null) {

		if ($engine) {
			$this->engine = $engine;
		} else {
			$default = self::DEFAULT_ENGINE;
			$this->engine = new $default;
		}

		$this->templateType = php_sapi_name() != "cli" ? 'html' : 'cli';

	}

	public function addBench(\Closure $callable) {
		$this->closures[] = $callable;
	}

	public function run(int $count = 1000000) {

		// Make sure the output of benchmarked code is not displayed.
		ob_start();

		// Set BC scale
		bcscale(self::BC_SCALE);

		// Limit the iterations count
		$count = min(max($count, self::MIN_COUNT), self::MAX_COUNT);

		try {
			$this->results = $this->engine->run($count, $this->closures);
		} catch (DifferentResultException $e) {
			echo $e->getMessage();
			die;
		}

		// Save the total count of iterations for the template
		$this->template['count'] = $count;

		// Render the output
		$this->renderResults();

	}

	private function renderResults() {

		ob_start();

		// Sort results from the best to the worst
		uasort($this->results, function($a, $b) {
			return $b < $a;
		});

		// Set up template variables
		$this->template['app_name'] = self::APP_NAME;
		$this->template['github_link'] = self::GITHUB_LINK;
		$this->template['css_file'] = self::pathToUrl(__DIR__ . "/../templates/css/main.css");
		$this->template['engine_used'] = $this->engine->getEngineName();
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
		require __DIR__ . sprintf("/../templates/%s.php", $this->templateType);

		ob_end_flush();

	}

	// Helpers

	public static function pathToUrl($path, $protocol = 'http://') {

		if (!isset($_SERVER['HTTP_HOST'])) return $path;

		// Correct the slash type
		$path = str_replace('\\', '/', $path);

		// Remove document root directory from the path
		$urlPath = str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), null, $path);

		return $protocol . $_SERVER['HTTP_HOST'] . $urlPath;

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

	private function getGradientColor(
		float $percentage,
		int $brightness = 200,
		$b = '00'
	) {

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
