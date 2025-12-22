<?php
class PEWErrorUI {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
	public static function getProtectedHTML($reqCtx, $pageTitle) {
		return $reqCtx
			->msg('pew-wiki-protected-edit-disallowed-msgbox')
			->params($pageTitle, $reqCtx->getUser()->getName())
			->plain();
	}
}