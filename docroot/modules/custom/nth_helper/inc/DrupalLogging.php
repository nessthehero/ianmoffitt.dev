<?php

	namespace Nth\Utils;

	use Drupal;

	class DrupalLogging
	{
		public static function Info($tag, $message)
		{
			$c = self::_CallingClass();
			if (!empty($c)) {
				$message = '[' . $c . '] ' . $message;
			}
			Drupal::logger($tag)->info($message);
		}

		public static function Error($tag, $message)
		{
			$c = self::_CallingClass();
			if (!empty($c)) {
				$message = '[' . $c . '] ' . $message;
			}
			Drupal::logger($tag)->error($message);
		}

		private static function _CallingClass()
		{
			$trace = debug_backtrace();
			if (isset($trace[2]['class'])) {
				return str_replace('Nth\\Utils\\', '', $trace[2]['class']);
			}

			return null;
		}
	}
