<?php
namespace Queue\Lib;

abstract class Status {
	const PENDING = 0;
	const IN_PROGRESS = 1;
	const FAILED = 2;
	const DISABLED = 3;
	const COMPLETE = 4;
}