<?php
require_once "ProtectEntireWiki.php";
use MediaWiki\Page\Hook\RollbackCompleteHook;
use MediaWiki\User\User;
class PEWRollbackingHooks implements RollbackCompleteHook {
	public function onRollbackComplete($wikiPage, $user, $rev, $curr) {
		$pew = ProtectEntireWiki::getInstance();
		if ($pew->canRollback($wikiPage->getTitle()->getNamespaceKey(), $user)) {
			return true;
		}
	}
}