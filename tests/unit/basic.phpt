<?php

use \Tester\Assert;

use \Smuuf\Phpcb\PhpBenchmark;
use \Smuuf\Phpcb\Engines\ChaoticEngine;
use \Smuuf\Phpcb\Engines\SerialEngine;

require __DIR__ . '/../bootstrap.php';

Assert::noError(fn() => new PhpBenchmark);

Assert::noError(fn() => new PhpBenchmark(new ChaoticEngine));
Assert::noError(fn() => new PhpBenchmark(new SerialEngine));

Assert::noError(fn() => new PhpBenchmark(new ChaoticEngine, PhpBenchmark::TEMPLATE_TYPE_CLI));
Assert::noError(fn() => new PhpBenchmark(new SerialEngine, PhpBenchmark::TEMPLATE_TYPE_HTML));
