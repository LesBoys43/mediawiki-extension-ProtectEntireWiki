<?php
class ProtectionConfigReader {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	public static readFrom($txt) {
		$txt_lines = explode("\n", $txt);
		return [trim($txt_lines[0]) == "*"];
	}
	public static readFromConf() {
		$loc = $wgPEWProtectionConfigFileLoc ?? $IP . "/extensions/ProtectEntireWiki/pconf.txt";
		if (!file_exists($loc)) {
			throw new LogicException("Check the LocalSettings, the protection config file not exist");
		}
		return self::readFrom(file_get_contents($loc));
	}
}