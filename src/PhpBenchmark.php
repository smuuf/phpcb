<?php

declare(strict_types=1);

namespace Smuuf\Phpcb;

use \Smuuf\Phpcb\Ex\DifferentResultException;
use \Smuuf\Phpcb\Engines\IEngine;

class PhpBenchmark {

	public const TEMPLATE_TYPE_CLI = 'cli';
	public const TEMPLATE_TYPE_HTML = 'html';

	private const APP_NAME = 'PHP Code Benchmark (phpcb)';
	private const GITHUB_LINK = 'http://github.com/smuuf/phpcb/';

	private const DEFAULT_ENGINE = \Smuuf\Phpcb\Engines\ChaoticEngine::class;
	private const BC_SCALE = 10;

	private array $closures = [];
	private array $results = [];
	private array $template = [];
	private string $templateType;

	private IEngine $engine;

	public function __construct(
		IEngine $engine = null,
		?string $templateType = null
	) {

		if ($engine) {
			$this->engine = $engine;
		} else {
			$default = self::DEFAULT_ENGINE;
			$this->engine = new $default;
		}

		$this->templateType = $templateType
			?: getenv('PHPCB_TEMPLATE_TYPE')
			?: (php_sapi_name() !== "cli"
				? self::TEMPLATE_TYPE_HTML
				: self::TEMPLATE_TYPE_CLI);

	}

	public function addBench(\Closure $callable) {
		$this->closures[] = $callable;
	}

	public function run(int $count = 1_000_000) {

		if (!$this->closures) {
			throw new \RuntimeException(
				"No callables specified to be benchmarked");
		}

		if ($count < 1) {
			throw new \RuntimeException(
				sprintf("Count must be at least 1 up to %s", PHP_INT_MAX));
		}

		// Set BC scale for later.
		bcscale(self::BC_SCALE);

		// Make sure the output of benchmarked code is not displayed.
		ob_start();

		try {
			$this->results = $this->engine->run($count, $this->closures);
		} catch (DifferentResultException $e) {
			echo $e->getMessage();
			die(1);
		}

		// Save the total count of iterations for the template
		$this->template['count'] = $count;

		// Render the output
		$this->renderResults();

	}

	private function renderResults(): void {

		// Sort results from the best to the worst
		uasort($this->results, fn($a, $b) => $a <=> $b);

		ob_start();

		// Set up template variables
		$this->template['app_name'] = self::APP_NAME;
		$this->template['github_link'] = self::GITHUB_LINK;
		$this->template['css_file'] = __DIR__ . '/Templates/css/main.css';
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
		require sprintf("%s/Templates/%s.phtml", __DIR__, $this->templateType);

		ob_end_flush();

	}

	// Helpers.

	private static function pathToUrl($path, $protocol = 'http://') {

		if (!isset($_SERVER['HTTP_HOST'])) {
			return $path;
		}

		// Correct the slash type
		$path = str_replace('\\', '/', $path);

		// Remove document root directory from the path
		$urlPath = str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '', $path);

		return $protocol . $_SERVER['HTTP_HOST'] . $urlPath;

	}

	// Template helpers.

	private static function getSourceCode(\Closure $closure) {

		$reflection = new \ReflectionFunction($closure);

		$file = $reflection->getFileName();
		$source = file($file);

		$startLine = $reflection->getStartLine();
		$endLine = $reflection->getEndLine();

		return implode(
			array_slice($source, $startLine - 1, $endLine - $startLine + 1)
		);

	}

	private function getGradientColor(
		float $percentage,
		int $brightness = 200,
		string $b = '00'
	) {

		$green = $brightness * $percentage / 100 ;
		$red = $brightness * (1 - ($percentage / 100));

		$compensate = ($brightness - abs($green - $red)) / 2;
		$green += (int) $compensate;
		$red += (int) $compensate;

		$r = str_pad(dechex((int) $red), 2, '0', STR_PAD_LEFT);
		$g = str_pad(dechex((int) $green), 2, '0', STR_PAD_LEFT);

		return "#$r$g$b";

	}

}
