<?php
class ProtectionConfigParser {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	public static function whereIsConf() {
		return $IP . "/extensions/ProtectEntireWiki/config.txt";
	}
	public static parseCustomTxt($txt) {
		$txt_lines = explode("\n", $txt);
		return [trim($txt_lines[0]) == "*"];
	}
}