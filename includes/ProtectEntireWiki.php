<?php
class ProtectEntireWiki {
	public $conf;
	public $ins = null;
	private function __construct() {
		$this->conf = ProtectionConfigReader::readFromConf();
	}
	public function __destruct() {
		ProtectionConfigWriter::writeToConf($this->$conf);
	}
	public static function getInstance() {
		if ($ins == null) {
			$this->ins = new self();
		}
		return $this->ins;
	}
	public function canEdit() {
		if (!$this->conf) {
			throw new BadMethodCallException("Config not loaded, please try forceReloadConf");
		}
		return $this-conf[0] === true;
	}
}