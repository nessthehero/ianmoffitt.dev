<?php

	// General functions to accomplish common tasks
	// Also includes other helper functions

	// Logging
	include_once(__DIR__ . '/DrupalLogging.php');

	// Utilities
	include_once(__DIR__ . '/utils/_index.php');

	// Preprocessing related helpers
	include_once(__DIR__ . '/helpers/_index.php');

	// Entity Finders
	include_once(__DIR__ . '/finders/_index.php');

	/**
	 * Replaces **string** with <strong>string</strong> in a heading.
	 *
	 * @param $heading
	 *
	 * @return string
	 */
	function parseHeading($heading)
	{
		$pattern = '/\*{2}(.+)\*{2}/';
		$replacement = '<strong>${1}</strong>';

		return preg_replace($pattern, $replacement, $heading);

	}
