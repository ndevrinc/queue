<?php
namespace Queue\Lib;

class Element_Type {
	const SCRIPT = 1;

	public function get_types() {
		$reflect = new \ReflectionClass( get_class( $this ) );

		return $reflect->getConstants();
	}
}
