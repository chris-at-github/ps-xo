<?php

namespace Ps\Xo\Service;

use Ps\Xo\Filter\DataProvider\AbstractDataProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class FilterService {

	/**
	 * objectManager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager;

	/**
	 * Plugin TypoScript Settings
	 *
	 * @var array $settings
	 */
	protected $settings;

	/**
	 * Filtereintrag (wird als Argument an den Konstruktor uebergeben)
	 *
	 * @var string $name
	 */
	protected $name;

	/**
	 * @var \TYPO3\CMS\Extbase\Mvc\Web\Request $request
	 */
	protected $request;

	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	protected $contentObject;

	/**
	 * Fix Parameter, die immer gelten und nicht durch den Anwender ueberschrieben werden koennen.
	 * Diese dienen ebenfalls DataProvidern um ihre Suchkriterien einzugrenzen z.B. Jahresauswahl von News
	 *
	 * @var array
	 */
	protected $persists = [];

	/**
	 * @param string $name
	 * @param \TYPO3\CMS\Extbase\Mvc\Web\Request $request
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject
	 * @return void
	 */
	public function __construct($name, $request, $contentObject) {
		$this->name = $name;
		$this->request = $request;
		$this->contentObject = $contentObject;

		$this->initializeSettings();
		$this->initializePersists();
	}

	/**
	 * return an instance of objectManager
	 *
	 * @return \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	public function getObjectManager() {
		if(($this->objectManager instanceof \TYPO3\CMS\Extbase\Object\ObjectManager) === false) {
			$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
		}

		return $this->objectManager;
	}

	/**
	 * liefert die TypoScript Plugin Einstellungen
	 *
	 * @return array
	 */
	public function initializeSettings() {
		if(isset($this->settings) === false) {
			$this->settings = $this->getObjectManager()->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class)->getConfiguration(
				\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'Xo'
			);
		}

		return $this->settings;
	}

	/**
	 * @return void
	 */
	public function initializePersists() {
		if(isset($this->settings['filter'][$this->name]['persists']) === true) {
			foreach($this->settings['filter'][$this->name]['persists'] as $persistName => $persistValue) {
				$this->persists[$persistName] = $persistValue;

				if(strpos($persistValue, ',') !== false) {
					$this->persists[$persistName] = GeneralUtility::trimExplode(',', $persistValue);
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function get() {
		$return = [];

		if(isset($this->settings['filter'][$this->name]) === true) {

			// FieldNamePrefix
			// @see: https://docs.typo3.org/typo3cms/ExtbaseGuide/Fluid/ViewHelper/Form.html#fieldnameprefix
			$return['namespace'] = $this->settings['filter'][$this->name]['namespace'];

			// Gruppierung aller Filter unter einem Identifier
			$return['identifier'] = $this->getIdentifier();

			// Record Ziel-ID (fuer den Ajax-Request)
			$return['record'] = $this->contentObject->data['uid'];

			// Ajax Page-Type
			$return['pageType'] = 0;
			if(empty($this->settings['filter'][$this->name]['pageType']) === false) {
				$return['pageType'] = (int) $this->settings['filter'][$this->name]['pageType'];
			}

			// Reset All Button
			if(empty($this->settings['filter'][$this->name]['resetAll']) === false) {
				$return['resetAll'] = $this->settings['filter'][$this->name]['resetAll'];
			}

			// Title durch Language-Datei gesetzt?
			$return['title'] = $this->settings['filter'][$this->name]['title'];
			if(empty($return['title']) === false && strpos($return['title'], 'LLL') !== false) {
				$return['title'] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($return['title']);
			}

			// Items
			$return['items'] = [];

			foreach($this->settings['filter'][$this->name]['items'] as $itemName => $itemProperties) {
				$return['items'][$itemName] = array_merge($itemProperties, [
					'name' => $itemName,
					'selected' => null
				]);

				// Label und Placeholder durch Language-Datei gesetzt?
				if(empty($return['items'][$itemName]['label']) === false && strpos($return['items'][$itemName]['label'], 'LLL') !== false) {
					$return['items'][$itemName]['label'] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($return['items'][$itemName]['label']);
				}

				if(empty($return['items'][$itemName]['placeholder']) === false && strpos($return['items'][$itemName]['placeholder'], 'LLL') !== false) {
					$return['items'][$itemName]['placeholder'] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($return['items'][$itemName]['placeholder']);
				}

				// Identifier erzeugen z.B. fuer Label oder Reset Button
				if(empty($return['items'][$itemName]['identifier']) === true) {
					$return['items'][$itemName]['identifier'] = 'fi' . md5($this->getIdentifier() . $itemName);
				}

				// Eventuelle Parameter verarbeiten
				$arguments = $this->getArguments();
				if(isset($arguments[$itemName]) === true) {
					$return['items'][$itemName]['selected'] = $arguments[$itemName];
				}

				// DataProvider
				if(isset($itemProperties['dataProvider']) === true) {

					// Immer ein Data Eintrag zur Verfuegung stellen
					if(isset($itemProperties['data']) === false) {
						$itemProperties['data'] = [];
					}

					foreach($itemProperties['dataProvider'] as $dataProviderFqcn => $dataProviderProperties) {
						/** @var AbstractDataProvider $dataProvider */
						$dataProvider = $this->getObjectManager()->get($dataProviderFqcn);
						$dataProvider->setFilter($this);

						$return['items'][$itemName] = $dataProvider->provide($return['items'][$itemName], $dataProviderProperties);
					}
				}
			}
		}

		return $return;
	}

	/**
	 * @param bool $dataProcessing
	 * @return array
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
	 */
	public function getArguments($dataProcessing = false) {
		$arguments = [];

		// Verarbeite fixierte Argumente (werden nicht durch Parameter ueberschrieben -> es wird geprueft ob dieser Eintrag
		// bereits existiert)
		$persists = $this->getPersists();

		foreach($persists as $persistName => $persistValue) {
			$arguments[$persistName] = $persistValue;
		}

		if($this->request->hasArgument($this->getIdentifier()) === true) {
			$request = $this->request->getArgument($this->getIdentifier());

			foreach($this->settings['filter'][$this->name]['items'] as $itemName => $itemProperties) {
				if(isset($request[$itemName]) === true && isset($arguments[$itemName]) === false) {
					$arguments[$itemName] = $request[$itemName];
				}
			}

			// Verarbeite Default-Variablen
		} else {
			foreach($this->settings['filter'][$this->name]['items'] as $itemName => $itemProperties) {
				if(isset($itemProperties['default']) === true && isset($arguments[$itemName]) === false) {
					$arguments[$itemName] = $itemProperties['default'];

					if(gettype($arguments[$itemName]) === 'string' && strpos($arguments[$itemName], ',') !== false) {
						$arguments[$itemName] = GeneralUtility::trimExplode(',', $arguments[$itemName]);
					}
				}
			}
		}

		if($dataProcessing === true) {
			foreach($this->settings['filter'][$this->name]['items'] as $itemName => $itemProperties) {

				// DataProcessor
				if(isset($itemProperties['dataProcessor']) === true) {
					foreach($itemProperties['dataProcessor'] as $dataProcessorFqcn => $dataProcessorProperties) {
						$dataProcessor = $this->getObjectManager()->get($dataProcessorFqcn);
						$dataProcessor->setFilter($this);
						$dataProcessor->setSettings($this->settings['filter']);

						$arguments[$itemName] = $dataProcessor->process($arguments[$itemName], $dataProcessorProperties, $itemProperties);
					}
				}
			}
		}

		return $arguments;
	}

	/**
	 * Erzeugt einen eindeutigen Identifier fuer die Formularelemente, falls mehrere Filter auf einer Seite vorhanden sind
	 *
	 * @return string
	 */
	public function getIdentifier() {

		// String Prefix muss vorhanden sein -> reiner Zahlenwert wirft Exception nach dem Absenden
		// @see: https://wiki.typo3.org/Exception/CMS/1210858767
		return md5($this->contentObject->data['uid']);
	}

	/**
	 * @return array
	 */
	public function getPersists() {
		return $this->persists;
	}

	/**
	 * @param array $persists
	 */
	public function setPersists(array $persists) {
		$this->persists = $persists;
	}

	/**
	 * Setzt ein neues fixiertes Argument
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function addPersits($name, $value) {
		$this->persists[$name] = $value;
	}

	/**
	 * Fuegt einen Defaultwert, falls dieser nicht ueber TS gesetzt werden kann, hinzu
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setDefaultArgument($name, $value) {
		if(isset($this->settings['filter'][$this->name]['items'][$name]) === true) {
			$this->settings['filter'][$this->name]['items'][$name]['default'] = $value;
		}
	}

	public function getDefaultArgument($name){
		if(isset($this->settings['filter'][$this->name]['items'][$name]) === true) {
			return $this->settings['filter'][$this->name]['items'][$name]['default'];
		}else return null;
	}
	/**
	 * Entfernt einen Defaultwert
	 *
	 * @param string $name
	 */
	public function resetDefaultArgument($name) {
		if(isset($this->settings['filter'][$this->name]['items'][$name]['default']) === true) {
			unset($this->settings['filter'][$this->name]['items'][$name]['default']);
		}
	}
}