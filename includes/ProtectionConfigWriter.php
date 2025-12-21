<?php
class ProtectionConfigWriter {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	public static function writeDataTo($data, $loc) {
		if (!file_exists($loc)) {
			throw new BadMethodCallException("Config file must exist");
		}
		$cont = implode("\n", $data);
		return file_put_contents($cont);
	}
	public static function writeDataToConf($data) {
		$loc = $wgPEWProtectionConfigFileLoc ?? $IP . "/extensions/ProtectEntireWiki/pconf.txt";
		return self::writeDataTo($data, $loc);
	}
}