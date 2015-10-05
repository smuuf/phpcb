<?php

namespace Smuuf\Phpcb;

interface IEngine {

	public function getEngineName();

	public function run($count, array $closures);

}
