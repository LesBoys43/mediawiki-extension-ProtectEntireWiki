<?php
class ProtectionConfigEncoder {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	public static function __encoding_map($ln) {
		if ($ln[1] == []) {
			return $ln[0];
		}
		return "${ln[0]}=${implode(",", $ln[1])}";
	}
	public static function encode($conf) {
		return array_map("self::__encoding_map", $conf);
	}
}