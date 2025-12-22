<?php
class PEWErrorUI {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	public static function getProtectedHTML($i18nCtx, $pageTitle) {
		return $i18nCtx->msg('pew-wiki-protected-edit-disallowed-msg1box')->params($pageTitle, $i18nCtx->getUser()->getName());
	}
}