<?php
namespace Queue\Lib;

class Element_Status {
	const PENDING = 1;
	const IN_PROGRESS = 2;
	const FAILED = 3;
	const DISABLED = 4;
	const COMPLETE = 5;

	public function get_types() {
		$reflect = new \ReflectionClass( get_class( $this ) );

		return $reflect->getConstants();
	}
}