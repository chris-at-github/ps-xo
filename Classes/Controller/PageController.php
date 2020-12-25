<?php

namespace Ps\Xo\Controller;

use Ps\Xo\Domain\Repository\PageRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Manage the Output for the Contact Locations.
 * @package Datamints\DatamintsContact\Controller
 */
class PageController extends ActionController {

	/**
	 * @param array $overwrite
	 * @return array
	 */
	protected function getDemand($overwrite = []) {
		$options = [];

		if(empty($this->settings['records']) === false) {
			$options['records'] = GeneralUtility::trimExplode(',', $this->settings['records'], true);
		}

		return $options;
	}

	/**
	 * @return void
	 */
	public function teaserAction() {
		$this->view->assign('pages', $this->objectManager->get(PageRepository::class)->findAll($this->getDemand()));
	}
}