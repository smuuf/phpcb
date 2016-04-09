<?php

require __DIR__ . "/../app/Loader.php";
$bench = new \Smuuf\Phpcb\PhpBenchmark;

define("FILENAME", "c:\some\very\hyper complex\file_path\image.png");

$bench->addBench(function() {
	$ext = substr(strrchr(FILENAME, '.'), 1);
});

$bench->addBench(function() {
	$ext = end(explode('.', FILENAME));
});

$bench->addBench(function() {
	$ext = substr(FILENAME, strrpos(FILENAME, '.') + 1);
});

$bench->addBench(function() {
	$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', FILENAME);
});

$bench->addBench(function() {
	$exts = preg_split("@[/\\.]@", FILENAME);
	$ext = end($exts);
});

$bench->addBench(function() {
	$ext = pathinfo(FILENAME, PATHINFO_EXTENSION);
});

$bench->run();
