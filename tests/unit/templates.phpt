<?php

use \Tester\Assert;

use \Smuuf\Phpcb\PhpBenchmark;
use \Smuuf\Phpcb\Engines\SerialEngine;
use \Smuuf\Phpcb\Engines\ChaoticEngine;

require __DIR__ . '/../bootstrap.php';

$phpcb = new PhpBenchmark(new ChaoticEngine, PhpBenchmark::TEMPLATE_TYPE_CLI);
$phpcb->addBench(fn() => usleep(1000));
$phpcb->addBench(fn() => usleep(2000));

ob_start();
$phpcb->run(10);
$output = ob_get_flush();
Assert::contains('Engine used', $output);
Assert::contains('Total time', $output);
Assert::contains('Iterations', $output);
Assert::contains('Score', $output);

$phpcb = new PhpBenchmark(new ChaoticEngine, PhpBenchmark::TEMPLATE_TYPE_HTML);
$phpcb->addBench(fn() => usleep(1000));
$phpcb->addBench(fn() => usleep(2000));

ob_start();
$phpcb->run(10);
$output = ob_get_flush();
Assert::match('#<span class="item".*Engine used#', $output);
Assert::match('#<span class="item".*Total time#', $output);
Assert::match('#<span class="item".*Iterations#', $output);
Assert::contains('<div class="score"', $output);
