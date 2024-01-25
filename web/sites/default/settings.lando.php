<?php

	// @codingStandardsIgnoreFile

	/**
	 * @file
	 * Lando-specific configuration file for local development.
	 */

	// Pull database information from the Lando config file.
	$lando_info = json_decode(getenv('LANDO_INFO'));
	$databases['default']['default'] = [
		'database' => $lando_info->database->creds->database,
		'username' => $lando_info->database->creds->user,
		'password' => $lando_info->database->creds->password,
		'host' => $lando_info->database->internal_connection->host,
		'port' => $lando_info->database->internal_connection->port,
		'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
		'driver' => $lando_info->database->type,
	];

	// Allow use of localhost or any other domains for local development.
	unset($settings['trusted_host_patterns']);

	// Local development settings. See example.settings.local.php for information.
	assert_options(ASSERT_ACTIVE, TRUE);
	\Drupal\Component\Assertion\Handle::register();
	$config['system.logging']['error_level'] = 'verbose';
	$config['system.performance']['css']['preprocess'] = FALSE;
	$config['system.performance']['js']['preprocess'] = FALSE;
	$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
	$settings['cache']['bins']['render'] = 'cache.backend.null';
	$settings['cache']['bins']['discovery_migration'] = 'cache.backend.memory';
	$settings['cache']['bins']['page'] = 'cache.backend.null';
	$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
	$settings['rebuild_access'] = TRUE;
	$settings['skip_permissions_hardening'] = TRUE;