<?php

namespace Smuuf\Phpcb\Ex;

class DifferentResultException extends \RuntimeException {

	public function __construct(string $msg, $currentResult, $previousResult) {

		$msg = "$msg\n█ Current result:\n" . print_r($currentResult, true)
			. "\n█ Previous result:\n" . print_r($previousResult, true);

		parent::__construct($msg);

	}

}
