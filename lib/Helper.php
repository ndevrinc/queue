<?php

namespace Queue\Lib;

if ( ! class_exists( 'Helper' ) ) {

	/**
	 * Class Helper
	 * @package Queue\Lib
	 */
	Class Helper {

		public static $text_domain = "Queue";

		/**
		 * Returns IP Address from request
		 *
		 * @return mixed
		 */
		public static function get_request_ip() {
			if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
				//check ip from share internet
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				//to check ip is pass from proxy
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}

			return $ip;
		}
	}
}
