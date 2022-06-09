<?php

	namespace Nth\Helpers;

  // TODO: Rewrite?

	class Date {

		/**
		 * Return a date field of a node in the user's timezone.
		 *
		 * @param $node
		 * @param $field
		 *
		 * @return string
		 */
		public static function date_in_default_timezone($node, $field, $format = 'Y-m-d H:i:s')
		{

			$return = '';

			date_default_timezone_set(date_default_timezone_get());

			$date = $node->get($field)->value;

			if (!empty($date)) {
				$date_original = new \Drupal\Core\Datetime\DrupalDateTime($date, 'UTC');

				if (!empty($date_original)) {
					$return = \Drupal::service('date.formatter')->format($date_original->getTimestamp(), 'custom', $format);
				}
			}

			return $return;

		}

		/**
		 * Return an array of information from a recurring date field. (date_recur module 2.*)
		 *
		 * 'next' has the next occurrence regardless if the date repeats or not.
		 *
		 * @param $date
		 *
		 * @return array
		 */
		public static function date_recur__dateobj($date)
		{

			// https://www.drupal.org/docs/8/modules/recurring-dates-field/date-recur-field-api

			$return = array();

			if (!empty($date[0]->value)) {

				$helper = $date[0]->getHelper();

				$occ = array();

				$timezone = date_default_timezone_get();

				$now = new \DateTime('now');
				$future = new \DateTime('now');
				$future->modify('+2 year');

				$gen = $helper->generateOccurrences(null, $future);

				foreach ($gen as $occurrence) {
					$occ[] = $occurrence;
				}

				if (!empty($occ)) {
					// $occ has all valid occurrences of a date between now and 2 years in the future

					$return['dates'] = $occ;

					$nowraw = $now->format('U');

					$latest = array();
					foreach ($occ as $o) {

						$start = $o->getStart()->format('U');
						$end = $o->getEnd()->format('U');

						if ($start >= $nowraw || ($start <= $nowraw && $end >= $nowraw)) {
							$latest = $o;
							break;
						}

					}

					$last = end($occ);

					if (!empty($latest)) {

						$return['next'] = array(
							'start' => $latest->getStart()->format('U'),
							'end'   => $latest->getEnd()->format('U')
						);

					} else {

						$return['next'] = array(
							'start' => $last->getStart()->format('U'),
							'end'   => $last->getEnd()->format('U')
						);

					}

					$return['diff'] = $return['next']['end'] - $return['next']['start'];

				}

			}

			return $return;

		}

		/**
		 * Helper that parses a date field into a formatted string.
		 *
		 * @param        $date
		 * @param string $format
		 *
		 * @return mixed
		 */
		public static function parse_date($date, $format = 'Y-m-d H:i:s P')
		{

			$formatted = \Drupal::service('date.formatter')->format(
				$date->getTimestamp(), 'custom', $format
			);

			return $formatted;

		}

		/**
		 * Helper that parses a Unix timestamp into a formatted date string.
		 *
		 * @param        $date
		 * @param string $format
		 *
		 * @return mixed
		 */
		public static function parse_timestamp($date, $format = 'Y-m-d H:i:s P')
		{
			$formatted = \Drupal::service('date.formatter')->format(
				$date, 'custom', $format
			);

			return $formatted;
		}

	}
