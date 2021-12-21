<?php

namespace Smuuf\Phpcb\Engines;

interface IEngine {

	public function getEngineName(): string;
	public function run(int $count, array $closures): array;

}
