<?php

require __DIR__ . "/../app/Loader.php";
$bench = new \Smuuf\Phpcb\PhpBenchmark;

$bench->addBench(function() {

	// Do stuff some way...

});

$bench->addBench(function() {

	// Do stuff some other way...

});

$bench->addBench(function() {

	// Do stuff some totally other way...

});

// Add more benchmarks...

// Run the benchmark (with default number of iterations)
$bench->run();
