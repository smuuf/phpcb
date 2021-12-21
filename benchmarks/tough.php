<?php

require __DIR__ . '/../vendor/autoload.php';
$bench = new \Smuuf\Phpcb\PhpBenchmark;

$bench->addBench(function() {
	usleep(10000);
});

$bench->addBench(function() {
	usleep(5000);
});

$bench->addBench(function() {
	usleep(2500);
});

$bench->addBench(function() {
	usleep(500);
});

$bench->addBench(function() {
	usleep(100);
});

$bench->addBench(function() {
	usleep(20000);
});

$bench->run(100);
