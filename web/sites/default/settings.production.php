<?php

	// @codingStandardsIgnoreFile

	$databases['default']['default'] = array (
		'database' => $_SERVER['IANMOFFITT_DATABASE_DBNAME'],
		'username' => $_SERVER['IANMOFFITT_DATABASE_USERNAME'],
		'password' => $_SERVER['IANMOFFITT_DATABASE_PASSWORD'],
		'prefix' => $_SERVER['IANMOFFITT_DATABASE_PREFIX'],
		'host' => $_SERVER['IANMOFFITT_DATABASE_HOST'],
		'port' => $_SERVER['IANMOFFITT_DATABASE_PORT'],
		'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
		'driver' => 'mysql',
	);

	/**
	 * Trusted host configuration.
	 *
	 * Drupal core can use the Symfony trusted host mechanism to prevent HTTP
	 * Host header spoofing.
	 *
	 * To enable the trusted host mechanism, you enable your allowable hosts
	 * in $settings['trusted_host_patterns']. This should be an array of
	 * regular
	 * expression patterns, without delimiters, representing the hosts you
	 * would
	 * like to allow.
	 *
	 * For example:
	 *
	 * @code
	 * $settings['trusted_host_patterns'] = [
	 *   '^www\.example\.com$',
	 * ];
	 * @endcode
	 * will allow the site to only run from www.example.com.
	 *
	 * If you are running multisite, or if you are running your site from
	 * different domain names (eg, you don't redirect http://www.example.com to
	 * http://example.com), you should specify all of the host patterns that
	 * are
	 * allowed by your site.
	 *
	 * For example:
	 * @code
	 * $settings['trusted_host_patterns'] = [
	 *   '^example\.com$',
	 *   '^.+\.example\.com$',
	 *   '^example\.org$',
	 *   '^.+\.example\.org$',
	 * ];
	 * @endcode
	 * will allow the site to run off of all variants of example.com and
	 * example.org, with all subdomains included.
	 */

	$settings['trusted_host_patterns'] = [
		'^ianmoffitt\.dev$',
		'^.+\.ianmoffitt\.dev$',
		'^www\.ianmoffitt\.dev$',
	];

	$settings['rebuild_access'] = FALSE;
