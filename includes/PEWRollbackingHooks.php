<?php
require_once "ProtectEntireWiki.php";
include_once "PEWErrorUI.php";
use MediaWiki\Page\Hook\RollbackCompleteHook;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\MediaWikiServices;
class PEWRollbackingHooks implements RollbackCompleteHook {
	public function onRollbackComplete($wikiPage, $user, $rev, $curr) {
		$pew = ProtectEntireWiki::getInstance();
		if ($pew->canRollback($wikiPage->getTitle()->getNamespaceKey(), $user)) {
			return true;
		}
		$actor = User::newFromName($wgPEWNotifyUser ?? "ProtectEntireWiki");
		$actor->addGroup("bureaucrat");
		$actor->addGroup("bot");
		$talkTitle = User::newFromIdentity($user)->getTalkPage();
		$ctx = RequestContext::getMain();
		$talk = Article::newFromTitle($talkTitle, $ctx)
				->getPage();
		$currRev = $talk->getRevisionRecord();
		$currTalkContent = $currRev
				->getSlots()
				->getSlot(SlotRecord::MAIN)
				->getContent();
		$currTalkWikitext = ContentHandler::getContentText($currTalkContent);
		# TODO: Use PageUpdater rewrite
		$revertedContent = $rev
				->getSlots()
				->getSlot(SlotRecord::MAIN)
				->getContent();
		$revertedWikitext = ContentHandler::getContentText($revertedContent);
		# TODO: Use PageUpdater rewrite
	}
}