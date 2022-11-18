<?php

	// @codingStandardsIgnoreFile

	$databases['default']['default'] = array (
		'database' => $_ENV['IANMOFFITT_DATABASE_DBNAME'],
		'username' => $_ENV['IANMOFFITT_DATABASE_USERNAME'],
		'password' => $_ENV['IANMOFFITT_DATABASE_PASSWORD'],
		'prefix' => $_ENV['IANMOFFITT_DATABASE_PREFIX'],
		'host' => $_ENV['IANMOFFITT_DATABASE_HOST'],
		'port' => $_ENV['IANMOFFITT_DATABASE_PORT'],
		'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
		'driver' => 'mysql',
	);