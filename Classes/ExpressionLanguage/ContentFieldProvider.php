<?php

namespace Ps\Xo\ExpressionLanguage;

use TYPO3\CMS\Core\ExpressionLanguage\AbstractProvider;
use Ps\Xo\ExpressionLanguage\FunctionsProvider\ContentElementFunctionsProvider;

/**
 * Ueberpruft ob bei einem neuen (GET:defVals) oder bestehenden TtContent-Element (Abfrage ueber UID) ob das abgefragte
 * Feld dem uebergebenen Werte(n) entspricht
 *
 * @see https://docs.typo3.org/typo3cms/TyposcriptReference/Conditions/Reference/Index.html#id53
 */
class ContentFieldProvider extends AbstractProvider {

	public function __construct()	{
		$this->expressionLanguageProviders = [
			ContentElementFunctionsProvider::class
		];
	}
}