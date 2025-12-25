<?php
class PEWErrorUI {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	public static function getProtectedHTML($reqCtx, $pageTitle, $s = false) {
		$template = "<div class=\"cdx-card\" id=\"pewbox\"><h3>__PEWBOX_TITLE__</h3><hr/><span class=\"cdx-card__text\" style=\"display: inline\"><span style=\"float: left\"><img src=\"https://upload.wikimedia.org/wikipedia/commons/f/f1/Stop_hand_nuvola.svg\" width=48 height=48 alt=\"Stop hand\"/></span><b>$2</b><bdi dir=\"ltr\">__PEWBOX_TEXT_BEFORE_PAGE__</bdi><b>$1</b><bdi dir=\"ltr\">__PEWBOX_TEXT_AFTER_PAGE__</bdi></span></div>";
		$actual = strtr(
			$template,
			[
				"__PEWBOX_TITLE__" => $reqCtx->msg($s ? "protectentirewiki-edit-savefailed-msgbox-title" : "protectentirewiki-edit-disallowed-msgbox-title")->plain(),
				"__PEWBOX_TEXT_BEFORE_PAGE__" => $reqCtx->msg("protectentirewiki-edit-generic-msgbox-text-before-page")->plain(),
				"__PEWBOX_TEXT_AFTER_PAGE__" => $reqCtx->msg("protectentirewiki-edit-generic-msgbox-text-after-page")->plain(),
				"$1" => $pageTitle,
				"$2" => $reqCtx->getUser()->getName()
			]
		);
		return $actual;
	}
	public static function getRollbackFailActorTalkpageWikitext($reqCtx, $pageTitle, $actor, $msgLeaver) {
		$template = "
== __PEW_ROLLBACK_FAILED_TITLE__ ==
[[File:Stop_hand_nuvola.svg|thumb|left|48px|48px]]'''$2'''__PEW_ROLLBACK_FAILED_TEXT_BEFORE_PAGE__'''$1'''__PEW_ROLLBACK_FAILED_TEXT_AFTER_PAGE____PEW_TP_MSG_SIGN__
		";
		$actual = strtr(
			$template,
			[
				"$2" => $pageTitle,
				"$1" => $actor->getName(),
				"__PEW_ROLLBACK_FAILED_TITLE__" => $reqCtx->msg("protectentirewiki-rollback-actionreverted-talkpage-msg-sect-title")->plain(),
				"__PEW_ROLLBACK_FAILED_TEXT_BEFORE_PAGE__" => $reqCtx->msg("protectentirewiki-rollback-actionreverted-talkpage-msg-text-before-page")->plain(),
				"__PEW_ROLLBACK_FAILED_TEXT_AFTER_PAGE__" => $reqCtx->msg("protectentirewiki-rollback-actionreverted-talkpage-msg-text-after-page")->plain(),
				"__PEW_TP_MSG_SIGN__" => $reqCtx->msg("protectentirewiki-rollback-actionreverted-talkpage-sign")->params($actor->getName())->plain()
			]
		);
		return $actual;
	}
}