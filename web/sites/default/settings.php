<?php

	include  __DIR__ . "/settings.common.php";

	/**
	 * If there is a local settings file, then include it.
	 */
	$local_settings = __DIR__ . "/settings.local.php";
	if (file_exists($local_settings)) {
		include $local_settings;
	}
// Automatically generated include for settings managed by ddev.
$ddev_settings = dirname(__FILE__) . '/settings.ddev.php';
if (getenv('IS_DDEV_PROJECT') == 'true' && is_readable($ddev_settings)) {
  require $ddev_settings;
}
