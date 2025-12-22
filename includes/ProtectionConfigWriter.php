<?php
include_once "ProtectionConfigEncoder.php";
class ProtectionConfigWriter {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	public static function writeTo($data, $loc) {
		if (!file_exists($loc)) {
			throw new BadMethodCallException("Config file must exist");
		}
		$cont = implode("\n", $data);
		return file_put_contents($cont);
	}
	public static function writeToConf($data) {
		global $IP;
		$loc = $wgPEWProtectionConfigFileLoc ?? $IP . "/extensions/ProtectEntireWiki/pconf.txt";
		return self::writeTo($data, $loc);
	}
}