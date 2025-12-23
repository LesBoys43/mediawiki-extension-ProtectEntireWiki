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
			# No ns-spec protection here, checking default protection
			if (!isset($conf["*"])) {
				return true;
			} else {
				foreach (user->getGroups() as $grp) {
					if (in_array($grp, $conf["*"]) {
						return true;
					}
				}
				return false;
			}
		}
		# Ns-spec protection has higher prio
		foreach (user->getGroups() as $grp) {
			if (in_array($grp, $conf[$ns]) {
				return true;
			}
		}
		return false;
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