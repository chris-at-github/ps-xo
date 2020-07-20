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
	 * @return void
	 */
	public function recordAction() {
		$options = [];

		if(empty($this->settings['records']) === false) {
			$options['records'] = GeneralUtility::trimExplode(',', $this->settings['records'], true);
		}

		$this->view->assign('records', $this->objectManager->get(AddressRepository::class)->findAll($options));
	}

	/**
	 * @return void
	 */
	public function mapAction() {
	}
}