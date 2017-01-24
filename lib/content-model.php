<?php

namespace Queue\Lib;

if ( ! class_exists( 'ContentModel' ) ) {

	/**
	 * Class ContentModel
	 * Content is going to be pulled and instantiate from within the Loader class
	 *
	 * @package Queue\Lib
	 */
	class ContentModel {
		/**
		 * List of custom post types
		 * @var array
		 */
		private $post_types = [
			'Queue\Lib\PostTypes\Log',
			'Queue\Lib\PostTypes\Queue',
		];

		/**
		 * List of helper classes
		 * @var array
		 */
		private $helper = [
		];


		/**
		 * @return array
		 */
		public function getPostTypes() {
			return $this->post_types;
		}

		/**
		 * @return array
		 */
		public function getHelper() {
			return $this->helper;
		}

	}
}
