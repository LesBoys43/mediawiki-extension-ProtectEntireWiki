<?php
include_once "ProtectEntireWiki.php";
include_once "PEWErrorUI.php";
use MediaWiki\Hook\EditPage__showEditForm_initialHook;
use MediaWiki\Context\RequestContext;
class PEWEditingUIHooksRw {
	public function onEditPage__showEditForm_initial($editor, $out = null) {
		$title = $editor->getTitle();
		$pew = ProtectEntireWiki::getInstance();
		if ($pew->canEdit()) {
			return;
		}
		# Cannot edit now, because entire wiki protected
		if ($out) {
			$ctx = $out->getContext();
		} else {
			# Use main context for fallback
			$ctx = RequestContext::getMain();
		}
		$html = PEWErrorUI::getProtectedHTML($ctx, $title);
		$editor->editFormPageTop .= $html;
	}
}