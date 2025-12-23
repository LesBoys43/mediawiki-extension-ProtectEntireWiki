<?php
include_once "ProtectEntireWiki.php";
include_once "PEWErrorUI.php";
use MediaWiki\Hook\EditPage__showEditForm_initialHook;
use MediaWiki\Context\RequestContext;
class PEWEditingUIHooksRw implements EditPage__showEditForm_initialHook{
	public function onEditPage__showEditForm_initial($editor, $out = null) {
		$title = $editor->getTitle();
		$pew = ProtectEntireWiki::getInstance();
		if ($out) {
			$ctx = $out->getContext();
		} else {
			# Use editor context for fallback
			$ctx = $editor->getContext();
		}
		$title = $editor->getArticle()->getTitle();
		if ($pew->canEdit($title->getNamespaceKey(), $ctx->getUser())) {
			return;
		}
		# Cannot edit now, because entire wiki protected
		# Vector (legacy) does not load codex autoly, load it manually here
		$editor->editFormPageTop .= "<script>window.addEventListener(\"load\", function(){mw.loader.load(\"@wikimedia/codex\")})</script>";
		# For fix codex card bugs, load some hotfix style
		$editor->editFormPageTop .= "<style>#pewbox{display: block;} #pewbox span.cdx-card__text{min-height: 40px;}</style>";
		$html = PEWErrorUI::getProtectedHTML($ctx, $title);
		$editor->editFormPageTop .= $html;
	}
}