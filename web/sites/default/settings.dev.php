<?php

	// @codingStandardsIgnoreFile
	// Allow use of localhost or any other domains for local development.
	unset($settings['trusted_host_patterns']);
	$databases['default']['default']['database'] = 'dev';
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
