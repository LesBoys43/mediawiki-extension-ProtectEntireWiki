<?php
class ProtectionConfigDecoder {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	
	public static function decode($cont) {
		$conf = [];
		$lns = explode("\n", $cont);
		foreach ($lns as $ln) {
			$parted = explode("=", trim($ln));
			switch (count($parted)):
				case 1:
					$conf[$parted[0]] = [bin2hex(random_bytes(24))];
					break;
				case 2:
					$conf[$parted[0]] = explode(",", $parted[1]);
					break;
				default:
					throw new LogicException("Bad config text, too many '=' in one line");
		}
		return $conf;
	}
}