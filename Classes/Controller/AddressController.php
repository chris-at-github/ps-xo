<?php

namespace Ps\Xo\Controller;

use Ps\Xo\Domain\Repository\AddressRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Manage the Output for the Contact Locations.
 * @package Datamints\DatamintsContact\Controller
 */
class AddressController extends ActionController {

	/**
	 * @param array $overwrite
	 * @return array
	 */
	protected function getDemand($overwrite = []) {
		$options = [];

		if(empty($this->settings['records']) === false) {
			$options['records'] = GeneralUtility::trimExplode(',', $this->settings['records'], true);
		}

		if(empty($this->settings['storagePid']) === false) {
			$options['storagePid'] = GeneralUtility::trimExplode(',', $this->settings['storagePid'], true);
		}

		return $options;
	}

	/**
	 * @return void
	 */
	public function recordAction() {
		$this->view->assign('address', $this->objectManager->get(AddressRepository::class)->setQuerySettings(['respectStoragePage' => false])->find($this->getDemand()));
		$this->view->assign('partial', ucfirst($this->settings['partial']));
	}
}