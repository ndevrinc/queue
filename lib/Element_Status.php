<?php
namespace Queue\Lib;

abstract class Element_Status {
	const PENDING = 1;
	const IN_PROGRESS = 2;
	const FAILED = 3;
	const DISABLED = 4;
	const COMPLETE = 5;
}