<?php

namespace Ps\Xo\Service;

use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * BackendPageLayoutService
 */
class BackendPageLayoutService {

	/**
	 * @see: https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Feature-82213-NewHookToDetermineIfContentRecordIsUsedUnused.html
	 *
	 * @param array $params
	 * @param PageLayoutView $parentObject
	 * @return bool
	 */
	public function isContentUsed(array $params, PageLayoutView $parentObject) {

		// wurde bereits als benutzt gekennzeichnet
		if($params['used'] === true) {
			return true;
		}

		// Flash Inhalte
		// @todo: colPos noch festlegen und per TS Konfiguration hinterlegen
		// @todo: Elternfeld fuer Inhalte hinzufuegen
		if((int) $params['record'] === 20 && empty($params['tx_xo_page']) === false) {
			return true;
		}

		return true;
	}
}