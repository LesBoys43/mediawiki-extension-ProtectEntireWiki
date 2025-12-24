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
		$talkTitle = User::newFromIdentity($user)->getTalkPage();
		$ctx = $wikiPage->getContext();
		$talk = Article::newFromTitle($tpTitle, $ctx)
				->getWikiPage();
		$currTalkContent = $talk->getRevisionRecord()
				->getSlots()
				->getSlot(SlotRecord::MAIN)
				->getContent();
		$currTalkWikitext = ContentHandler::getContent($oldContent);
		# TODO
		$newWikitext = $oldWikitext . "sth";
		$newRev = new RevisionRecord(
			$talk,
			new RevisionSlots([
				SlotRecord::newUnsaved(
					SlotRecord::MAIN,
					new WikitextContent($newWikitext)
				);
			])
		);
		$actor = User::newFromName($wgPEWNotifyUser ?? "ProtectEntireWiki");
		$actor->addGroup("bureaucrat");
		$actor->addGroup("bot");
		$talk->doEditUpdates($newRev, $actor);
		# We need rollback the rollback
		$wikiPage->doEditUpdates($curr, $actor);
	}
}