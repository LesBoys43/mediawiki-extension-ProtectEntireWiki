<?php
require_once "ProtectEntireWiki.php";
include_once "PEWErrorUI.php";
use MediaWiki\Page\Hook\RollbackCompleteHook;
use MediaWiki\Revision\SlotRecord;
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
		$talkUpdater = $talk->newPageUpdater($actor);
		$talkUpdater->setContent(SlotRecord::MAIN, new WikitextContent($newWikitext));
		$talkNewRev = $talkUpdater->saveRevision(
			CommentStoreComment::newUnsavedComment($ctx->msg("protectentirewiki-rollback-actionreverted-talkpage-editsummary")),
			EDIT_UPDATE, EDIT_SUPPRESS_RC
		);
		$talk->doEditUpdates($talkNewRev, $actor);
		$talk->doPurge();
		$revertedContent = $rev
				->getSlots()
				->getSlot(SlotRecord::MAIN)
				->getContent();
		$wpUpdater = $wikiPage->newPageUpdater($actor);
		$wpUpdater->setContent(SlotRecord::MAIN, $revertedContent);
		$wpNewRev = $wpUpdater->saveRevision(
			CommentStoreComment::newUnsavedComment($ctx->msg("protectentirewiki-rollback-actionreverted-rbrb-editsummary")),
			EDIT_UPDATE, EDIT_SUPPRESS_RC
		);
		try {
			$wikiPage->doEditUpdates($wpNewRev, $actor);
		} catch (TypeError $e) {
			# FIXME
		}
	}
}