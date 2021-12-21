<?php

require __DIR__ . '/../vendor/autoload.php';
$bench = new \Smuuf\Phpcb\PhpBenchmark;

const COUNT = 100;

$bench->addBench(function() {
	for ($i = 1; $i <= COUNT; $i++) {}
});

$bench->addBench(function() {
	for ($i = COUNT; $i > 0; $i--) {}
});

$bench->addBench(function() {
	for ($i = COUNT; $i--;) {}
});

$bench->addBench(function() {
	for ($i = -COUNT; $i++;) {}
});

$bench->run();
