<?php

namespace Ps\Xo\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * The domain model of a Address
 *
 * @entity
 */
class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category {

	/**
	 * @var string
	 */
	protected $link;

	/**
	 * @return string
	 */
	public function getLink(): string {
		return $this->link;
	}

	/**
	 * @param string $link
	 */
	public function setLink(string $link): void {
		$this->link = $link;
	}

	/**
	 * @return string
	 */
	public function getLinkUri() {

		/** @var ContentObjectRenderer $contentObjectRenderer */
		$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);

		return $contentObjectRenderer->typolink_URL([
			'parameter' => $this->getLink(),
			'useCashHash' => false,
			'returnLast' => 'url',
			'forceAbsoluteUrl' => false
		]);
	}
}