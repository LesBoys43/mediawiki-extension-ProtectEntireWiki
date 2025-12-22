<?php
include_once "ProtectEntireWiki.php";
use MediaWiki\Hook\EditPage__showEditForm_initialHook;
class PEWEditingUIHooksRw {
	public funciton onEditPage__showEditForm_initial($editor, $out = null) {
		$title = $editor->getTitle();
		$pew = protectEntireWiki::getInstance();
		if ($pew->canEdit()) {
			return;
		}
		# Cannot edit now, because entire wiki protected
		# TODO: Render error info
	}
}