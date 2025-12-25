<?php
require_once "ProtectEntireWiki.php";
include_once "PEWErrorUI.php";
use MediaWiki\Page\Hook\RollbackCompleteHook;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionSlots;
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
		$currTalkContent = $talk->getRevisionRecord()
				->getSlots()
				->getSlot(SlotRecord::MAIN)
				->getContent();
		$currTalkWikitext = ContentHandler::getContentText($currTalkContent);
		$noticeMsg = PEWErrorUI::getRollbackFailActorTalkpageWikitext($ctx, strval($wikiPage->getTitle()), $user, $actor);
		$newWikitext = $oldWikitext . $noticeMsg;
		$newSlot = SlotRecord::newUnsaved(
				SlotRecord::MAIN,
				new WikitextContent($newWikitext)
			);
			
		$newRev = new MutableRevisionRecord($talk);
		$newRev->setSlot($newSlot);
		$talk->doEditUpdates($newRev, $actor);
		# We need rollback the rollback
		$wikiPage->doEditUpdates($curr, $actor);
	}
}