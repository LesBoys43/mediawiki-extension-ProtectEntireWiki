<?php
include "ProtectionConfigDecoder.php";
class ProtectionConfigReader {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	public static function readFrom($txt) {
		return ProtectionConfigDecoder::decode($txt);
	}
	public static function readFromConf() {
		global $IP;
		$loc = $wgPEWProtectionConfigFileLoc ?? $IP . "/extensions/ProtectEntireWiki/pconf.txt";
		if (!file_exists($loc)) {
			throw new LogicException("Config file must exist");
		}
		return self::readFrom(file_get_contents($loc));
	}
}