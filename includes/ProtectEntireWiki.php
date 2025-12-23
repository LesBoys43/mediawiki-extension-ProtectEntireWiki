<?php
include_once "ProtectionConfigReader.php";
include_once "ProtectionConfigWriter.php";
class ProtectEntireWiki {
	protected $conf;
	private static $ins = null;
	private function __construct() {
		$this->conf = ProtectionConfigReader::readFromConf();
	}
	public function __destruct() {
		$this->save();
	}
	public static function getInstance() {
		if (self::$ins == null) {
			self::$ins = new self();
		}
		return self::$ins;
	}
	public function forceReloadConf() {
		$this->conf = ProtectionConfigReader::readFromConf();
	}
	public function canEdit($ns = "*", $user = null) {
		if (!$this->conf) {
			throw new BadMethodCallException("Config not loaded, please try forceReloadConf");
		}
		if (!isset($conf[strval($ns)])) {
			return true;
		}
	}
	public function changeProt($canEdit) {
		if (!$this->conf) {
			throw new BadMethodCallException("Config not loaded, please try forceReloadConf");
		}
		$this->conf[0] = $canEdit;
	}
	public function save() {
		ProtectionConfigWriter::writeToConf($this->conf);
	}
}