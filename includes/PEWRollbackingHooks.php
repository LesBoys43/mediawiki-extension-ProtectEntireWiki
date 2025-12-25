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
		$noticeMsg = PEWErrorUI::getRollbackFailActorTalkpageWikitext($ctx, strval($wikiPage->getTitle()), $user, $actor);
		$newWikitext = $currTalkWikitext . $noticeMsg;
		$newSlot = SlotRecord::newUnsaved(
				SlotRecord::MAIN,
				new WikitextContent($newWikitext)
			);
		$revStore = MediaWikiServices::getInstance()->getRevisionStore();
		$newRev = new MutableRevisionRecord($talk);
		$newRev->setSlot($newSlot);
		$newRev->setUser($actor);
		$newRev->setPageId($talk->getId());
		$newRev->setTimestamp(wfTimestampNow());
		$newRev->setComment(
			CommentStoreComment::newUnsavedComment($ctx->msg("protectentirewiki-rollback-actionreverted-talkpage-editsummary"))
		);
		$dbw = MediaWikiServices::getInstance()->getDBLoadBalancer()->getMaintenanceConnectionRef(DB_PRIMARY);
		$savedRev = $revStore->insertRevisionOn($newRev, $dbw);
		$talk->doEditUpdates($savedRev, $actor, [
			"changed" => true,
			"oldrevision" => $currRev
		]);
		$talk->updateRevisionOn($dbw, $savedRev, $savedRev->getId(), false);
		# We need rollback the rollback
		$wikiPage->doEditUpdates($rev, $actor, [
			"changed" => true,
			"oldrevision" => $curr
		]);
		$wikiPage->updateRevisionOn($dbw, $rev, $rev->getId(), false);
	}
}