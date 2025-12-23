<?php
include_once "ProtectEntireWiki.php";
include_once "PEWErrorUI.php";
use MediaWiki\Hook\EditPage__attemptSave;
use MediaWiki\Context\RequestContext;
use MediaWiki\Logger\LoggerFactory;
class PEWEditingUIHooksRw implements EditPage__attemptSave{
	public function onEditPage__attemptSave($editor) {
		$title = $editor->getTitle();
		$pew = ProtectEntireWiki::getInstance();
		$ctx = $editor->getContext();
		$title = $editor->getArticle()->getTitle();
		if ($pew->canEdit($title->getNamespaceKey(), $ctx->getUser())) {
			return;
		}
		# Cannot edit, shows msg and aborts
		$editor->editFormPageTop .= "<script>window.addEventListener(\"load\", function(){mw.loader.load(\"@wikimedia/codex\")})</script>";
		# See PEWEditingUIHooksRw.php L26
		$editor->editFormPageTop .= "<style>#pewbox{display: block; min-height: 96px;}</style>";
		$html = PEWErrorUI::getProtectedHTML($ctx, $title);
		$editor->editFormPageTop .= $html;
		return false;
	}
}