<?php

use \Tester\Assert;

use \Smuuf\Phpcb\PhpBenchmark;
use \Smuuf\Phpcb\Engines\ChaoticEngine;
use \Smuuf\Phpcb\Engines\SerialEngine;

require __DIR__ . '/../bootstrap.php';

$phpcb = new PhpBenchmark(new ChaoticEngine, PhpBenchmark::TEMPLATE_TYPE_CLI);

Assert::exception(
	fn() => $phpcb->run(10),
	RuntimeException::class,
	'#No callables.*specified#'
);

$phpcb->addBench(fn() => usleep(1000));
$phpcb->addBench(fn() => usleep(2000));

Assert::exception(fn() => $phpcb->run(0), RuntimeException::class, '#Count must be#');
Assert::exception(fn() => $phpcb->run(0.9), RuntimeException::class, '#Count must be#');
Assert::exception(fn() => $phpcb->run(-1), RuntimeException::class, '#Count must be#');
Assert::exception(fn() => $phpcb->run(-1000), RuntimeException::class, '#Count must be#');

// Too large integers (which would be passed as a float by PHP itself) will
// cause a direct TypeError during the call itself - we don't need to do any
// checking outselves.
Assert::exception(fn() => $phpcb->run(123456789123456789123456789123456789), TypeError::class);
