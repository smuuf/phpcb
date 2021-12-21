[![PHP tests](https://github.com/smuuf/phpcb/actions/workflows/php.yml/badge.svg)](https://github.com/smuuf/phpcb/actions/workflows/php.yml)

# phpcb (php code benchmark)

**phpcb** is a very simple and very lightweight tool for speed benchmarking of various little pieces of PHP code, written in PHP, of course.

### Why
**phpcb** is meant to be used in those situations when there are multiple ways of how to do something - and you *know* all will have the exact same result - but you ***just can't*** decide which would ultimately be the best (meaning "*fastest*") to use.

### Requirements
- PHP 7.4+
- *BCMath Arbitrary Precision Mathematics* library for PHP; shouldn't be a problem, since it is commonly shipped with PHP itself.

### Installation
```
composer require --dev smuuf/-hpcb
```

### Usage

Write your microbenchmarks in a some file. For example `mymicrobench.php` _(here placed in phpcb's root directory, so it's clear we rquire Composer's autoload file from correct place)_:
```php
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
```

And then run it:
```bash
$ php ./mymicrobench.php
```

And observe results:

```php
█ PHP Code Benchmark (phpcb)
█ PHP 7.4.27

Engine used: Chaotic Engine
Total time: 1.3220 sec
Iterations: 1 000 000

██ 2. Score: 100.00, 0.2660 sec
┌
│ $bench->addBench(function() {
│       for ($i = COUNT; $i > 0; $i--) {}
│ });
└

██ 1. Score: 86.54, 0.3074 sec, 1.16x slower
┌
│ $bench->addBench(function() {
│       for ($i = 1; $i <= COUNT; $i++) {}
│ });
└

██ 4. Score: 71.13, 0.3740 sec, 1.41x slower
┌
│ $bench->addBench(function() {
│       for ($i = -COUNT; $i++;) {}
│ });
└

██ 3. Score: 71.02, 0.3746 sec, 1.41x slower
┌
│ $bench->addBench(function() {
│       for ($i = COUNT; $i--;) {}
│ });
└
```
