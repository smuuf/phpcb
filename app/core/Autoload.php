<?php

namespace Smuuf\Phpcb;

class Autoloader {

	/**
	 * @var array
	 *
	 * File paths (relative to this autoloader's directory)
	 * mappings to phpcb's classes.
	 *
	 * - Class names are defined relative to the \Smuuf\Phpcb namespace
	 *
	 * - File path must begin with slash and mustn't end with '.php' as
	 * it will be added automatically.
	 *
	 * If no match is found during the simple mapping, complex matching
	 * will be performed:
	 * Correct class name match from the left (key) side will be
	 * injected instead of asterisk on the right (value) side.
	 *
	 * Eg. 'SerialEngine' (matching '*Engine') => '/engines/SerialEngine'
	 */
	protected static $mapping = [
		'PhpBenchmark' => '/PhpBenchmark',
		'*Engine' => '/engines/*',
		'I*' => '/interfaces/*',
		'*Exception' => '/exceptions/*',
	];

	/** @var bool Was this autoloader already registered? **/
	protected static $registered = false;

	public static function register() {

		// If this autoloader is already registered, go away.
		if (self::$registered) return;

		// Register autoloading.
		self::$registered = spl_autoload_register([__CLASS__, 'loadClass'], true, false);

	}

	public static function loadClass($name) {

		$relativeClass = str_replace(__NAMESPACE__ . '\\', null, $name);

		// Simple mapping
		if (isset(self::$mapping[$relativeClass])) {
			require_once self::getAbsolutePath(self::$mapping[$relativeClass]);
		}

		// Complex wildcard matching, try all pairs.
		foreach (self::$mapping as $class => $path) {

			// If there's no wildcard at all, skip this pair.
			if (strpos($class, '*') === false) continue;

			// Try if the class name wildcard matches.
			$regexClass = self::wildcardToRegex($class);
			if (preg_match($regexClass, $relativeClass, $matches) == false) continue;

			// Take the first match...
			$match = reset($matches);

			// ...replace the asterisk in the path with it
			// and try if the final absolute path exists.
			$path = str_replace('*', $match, $path);

			if (is_readable($finalPath = self::getAbsolutePath($path))) {
				require_once $finalPath;
			}

		}

	}

	protected static function getAbsolutePath($relative) {
		return __DIR__ . $relative . '.php';
	}

	protected static function wildcardToRegex($wild) {
		return '#' . str_replace('*', '.*', $wild) . '#';
	}

}
