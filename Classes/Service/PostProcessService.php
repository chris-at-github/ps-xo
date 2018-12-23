<?php

namespace Ps\Xo\Service;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class PostProcessService {

	/**
	 * @param array $params
	 * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $pObj
	 */
	public function outputNoCache(&$params, &$pObj) {
		$params['pObj']->content = $this->replace($params['pObj']->content);
	}

	/**
	 * @param array $params
	 * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $pObj
	 */
	public function outputCache(&$params, &$pObj) {
		$params['pObj']->content = $this->replace($params['pObj']->content);
	}

	protected function replace($html) {

		// Entferne leere Klassen-Attribute
		$html = str_replace(' class=""', null, $html);

		// Entferne unnoetige Leerzeilen
		$html = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $html);

		return $html;
	}
}