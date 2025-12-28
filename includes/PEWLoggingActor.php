<?php
class PEWLoggingActor {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
}