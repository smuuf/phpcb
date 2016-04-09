<?php

require __DIR__ . "/../app/Loader.php";
$bench = new \Smuuf\Phpcb\PhpBenchmark;

// Set up everything we need.
$variables = [];
$variables[] = null;
$variables[] = '';
$variables[] = '0';
$variables[] = 0;

// Add benchmarks.
$bench->addBench(function() use ($variables) {

	foreach ($variables as $v) {
		!$v;
	}

});

$bench->addBench(function() use ($variables) {

	foreach ($variables as $v) {
		empty($v);
	}

});

$bench->addBench(function() use ($variables) {

	foreach ($variables as $v) {
		$v == 0;
	}

});

// Run the benchmark (with default number of iterations)
$bench->run(100000);
