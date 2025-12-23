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
				"__PEWBOX_TEXT_BEFORE_PAGE__" => $reqCtx->msg("protectentirewiki-edit-disallowed-msgbox-text-before-page")->plain(),
				"__PEWBOX_TEXT_AFTER_PAGE__" => $reqCtx->msg("protectentirewiki-edit-disallowed-msgbox-text-after-page")->plain(),
				"$1" => $pageTitle,
				"$2" => $reqCtx->getUser()->getName()
			]
		);
		return $actual;
	}
}