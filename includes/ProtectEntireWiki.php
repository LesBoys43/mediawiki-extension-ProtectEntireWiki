<?php
include_once "ProtectionConfigReader.php";
include_once "ProtectionConfigWriter.php";
require_once "PEWEditingUIHooksRw.php";
require_once "PEWEditingSavingHooks.php";
require_once "PEWRollbackingHooks.php";
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
		if (!isset($this->conf[strval($ns)])) {
			# No ns-spec protection here, checking default protection
			if (!isset($this->conf["*"])) {
				return true;
			} else {
				if ($user == null) {
					# User not provided, only check the everybody is allowed or not
					return in_array("*", $this->conf["*"]);
				}
				foreach ($user->getGroups() as $grp) {
					if (in_array($grp, $this->conf["*"])) {
						return true;
					}
				}
				return false;
			}
		}
		# Ns-spec protection has higher prio
		if ($user == null) {
			return in_array("*", $this->conf[ns]);
		}
		foreach ($user->getGroups() as $grp) {
			if (in_array($grp, $this->conf[$ns])) {
				return true;
			}
		}
		return false;
	}
	public function canRollback($ns = "*", $user = null) {
		if ($this->canEdit($ns, $user)) {
			# Can edit implies can rollback
			return true;
		}
		if (!isset($this->conf[strval($ns)])) {
			# Rollbacks defaully allowed
			return true;
		}
		return !in_array("disallow-rollback", $this->conf[strval($ns)]);
	}
	public function changeProt($canEdit) {
		# TODO: rewrite for new design
		if (!$this->conf) {
			throw new BadMethodCallException("Config not loaded, please try forceReloadConf");
		}
		$this->conf[0] = $canEdit;
	}
	public function save() {
		ProtectionConfigWriter::writeToConf($this->conf);
	}
}