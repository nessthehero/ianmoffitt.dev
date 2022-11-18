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