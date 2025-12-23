<?php
include_once "ProtectEntireWiki.php";
include_once "PEWErrorUI.php";
use MediaWiki\Hook\EditPage__showEditForm_initialHook;
use MediaWiki\Context\RequestContext;
use MediaWiki\Logger\LoggerFactory;
class PEWEditingUIHooksRw implements EditPage__showEditForm_initialHook{
	public function onEditPage__showEditForm_initial($editor, $out = null) {
		$logger = LoggerFactory::getInstance("PEWDefaultLogger");
		$title = $editor->getTitle();
		$pew = ProtectEntireWiki::getInstance();
		if ($out) {
			$ctx = $out->getContext();
		} else {
			# Use editor context for fallback
			$ctx = $editor->getContext();
		}
		$title = $editor->getArticle()->getTitle();
		$logger->debug("Namespace key of $title is {$title->getNamespaceKey()}");
		if ($pew->canEdit($title->getNamespaceKey(), $ctx->getUser())) {
			return;
		}
		# Cannot edit now, because entire wiki protected
		if (isset($editor->__pew_dont_show_disallowed_msgbox) && $editor->__pew_dont_show_disallowed_msgbox) {
			# The page is in saving step, we should not add the disallowed msgbox, saveing hook will add its own msgbox
			return;
		}
		# Vector (legacy) does not load codex autoly, load it manually here
		$editor->editFormPageTop .= "<script>window.addEventListener(\"load\", function(){mw.loader.load(\"@wikimedia/codex\")})</script>";
		# For fix codex card bugs, load some hotfix style
		$editor->editFormPageTop .= "<style>#pewbox{display: block; min-height: 96px;}</style>";
		$html = PEWErrorUI::getProtectedHTML($ctx, $title);
		$editor->editFormPageTop .= $html;
	}
}