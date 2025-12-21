<?php
class ProtectEntireWiki {
	public $conf;
	public $ins = null;
	private function __construct() {
		this->conf = ProtectionConfigParsr::parseFromConf();
	}
	public function __destruct() {
		ProtectionConfigWriter::writeToConf(this->$conf);
	}
	public static function getInstance() {
		if ($ins == null) {
			this->ins = new self();
		}
		return this->ins;
	}
}