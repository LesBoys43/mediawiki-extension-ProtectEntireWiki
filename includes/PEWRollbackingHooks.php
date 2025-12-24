<?php
require_once "ProtectEntireWiki.php";
use MediaWiki\Page\Hook\RollbackCompleteHook;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\RevisionSlots;
class PEWRollbackingHooks implements RollbackCompleteHook {
	public function onRollbackComplete($wikiPage, $user, $rev, $curr) {
		$pew = ProtectEntireWiki::getInstance();
		if ($pew->canRollback($wikiPage->getTitle()->getNamespaceKey(), $user)) {
			return true;
		}
		$tpTitle = User::newFromIdentity($user)->getTalkPage();
		$ctx = $wikiPage->getContext();
		$tp = Article::newFromTitle($tpTitle, $ctx);
		$tpWp = $tp->getWikiPage();
		$oldRev = $tpWp->getRevisionRecord();
		$oldRevSlots = $oldRev->getSlots();
		$oldMain = $oldRevSlots->getSlot(SlotRecord::MAIN);
		$oldCont = $oldMain->getContent();
		$oldWt = ContentHandler::getContent($oldCont);
		# TODO
		$newWt = $oldWt . "sth";
		$newCont = new WikitextContent($newWt);
		$newSlot = SlotRecord::newUnsaved(SlotRecord::MAIN, $newCont);
		$newRevSlots = new RevisionSlots([$newSlot]);
		$newRev = new RevisionRecord($tpWp, $newRevSlots);
		$actor = User::newFromName($wgPEWNotifyUser ?? "ProtectEntireWiki");
		$actor->addGroup("bureaucrat");
		$actor->addGroup("bot");
		$tpWp->doEditUpdates($newRev, $actor);
	}
}