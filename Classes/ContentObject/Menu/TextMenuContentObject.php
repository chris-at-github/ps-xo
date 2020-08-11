<?php
namespace Ps\Xo\ContentObject\Menu;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Extension class creating text based menus
 */
class TextMenuContentObject extends \TYPO3\CMS\Frontend\ContentObject\Menu\TextMenuContentObject {

	const PAGE_TYPE_TYPOLINK = 120;

	/**
	 * Creates the URL, target and onclick values for the menu item link. Returns them in an array as key/value pairs for <A>-tag attributes
	 * This function doesn't care about the url, because if we let the url be redirected, it will be logged in the stat!!!
	 *
	 * @param int $key Pointer to a key in the $this->menuArr array where the value for that key represents the menu item we are linking to (page record)
	 * @param string $altTarget Alternative target
	 * @param string $typeOverride Alternative type
	 * @return array Returns an array with A-tag attributes as key/value pairs (HREF, TARGET and onClick)
	 */
	protected function link($key, $altTarget = '', $typeOverride = '') {
		if($this->menuArr[$key]['doktype'] == self::PAGE_TYPE_TYPOLINK) {
			return [
				'HREF' => $this->parent_cObj->typoLink_URL(['parameter' => $this->menuArr[$key]['url']]),
				'TARGET' => $this->menuArr[$key]['target'],
				'onClick' => ''
			];
		}

		return parent::link($key, $altTarget, $typeOverride);
	}
}

