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
Assert::exception(fn() => $phpcb->run(-1), RuntimeException::class, '#Count must be#');
Assert::exception(fn() => $phpcb->run(-1000), RuntimeException::class, '#Count must be#');

if (PHP_VERSION_ID < 80100) {

	// PHP 8.0 and less is okay with this (passing float to int parameter),
	// but we do our check inside the run() method.
	Assert::exception(fn() => $phpcb->run(0.9), RuntimeException::class, '#Count must be#');

} else {

	// PHP 8.1 raises a deprecation error (and we also expect our runtime
	// exception outside of it).
	Assert::exception(function() use ($phpcb) {
		Assert::error(fn() => $phpcb->run(0.9), E_DEPRECATED, '#Implicit conversion#');
	}, RuntimeException::class, '#Count must be#');

}

// Too large integers (which would be passed as a float by PHP itself) will
// cause a direct TypeError during the call itself - we don't need to do any
// checking outselves.
Assert::exception(fn() => $phpcb->run(123456789123456789123456789123456789), TypeError::class);
