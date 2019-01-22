<?php

namespace Ps\Xo\Service;

/**
 * Json Service Class
 */
class JsonService {

	/**
	 * toJson
	 *
	 * @param mixed $value
	 * @param array $options
	 * @return string
	 */
	public function toJson($value, $options = []) {
		if(gettype($value) === 'object') {
			$value = $this->toArray($value, $options);
		}

		return json_encode($value);
	}

	/**
	 * toArray
	 *
	 * @param mixed $value
	 * @param array $options
	 * @return array
	 */
	public function toArray($value, $options = []) {
		if($value instanceof \TYPO3\CMS\Extbase\DomainObject\AbstractEntity) {
			$value = $this->extbaseObjectToArray($value, $options);
		}

		if($value instanceof \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult || $value instanceof \TYPO3\CMS\Extbase\Persistence\ObjectStorage) {
			$value = $this->extbaseCollectionToArray($value, $options);
		}

		if($value instanceof \DateTime) {
			$value = $this->datetimeToArray($value, $options);
		}

		return $value;
	}

	/**
	 * toJson -> ExtbaseObject
	 *
	 * @param \TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject $object
	 * @param array $options
	 * @return array
	 */
	public function extbaseObjectToArray(\TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject $object, $options) {
		$data = [];
		$properties = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettablePropertyNames($object);

		foreach($properties as $propertyName) {
			if(empty($options) === true || isset($options[$propertyName]) === true || in_array($propertyName, $options) === true) {
				$propertyValue = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($object, $propertyName);

				if(gettype($propertyValue) === 'array' || gettype($propertyValue) === 'object') {

					$propertyOptions = [];
					if(isset($options[$propertyName]) === true && gettype($options[$propertyName]) === 'array') {
						$propertyOptions = $options[$propertyName];
					}

					if($propertyValue instanceof \TYPO3\CMS\Extbase\DomainObject\AbstractEntity || $propertyValue instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {

						if(empty($propertyOptions) === false) {
							if($propertyValue instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
								$propertyValue = $propertyValue->_loadRealInstance();
							}

							$propertyValue = $this->extbaseObjectToArray($propertyValue, $propertyOptions);

						} else {
							$propertyValue = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($propertyValue, 'uid');
						}

					} elseif($propertyValue instanceof \TYPO3\CMS\Extbase\Persistence\ObjectStorage) {
						$propertyValue = $this->extbaseCollectionToArray($propertyValue, $propertyOptions);

					} elseif($propertyValue instanceof \DateTime) {
						$propertyValue = $this->datetimeToArray($propertyValue, $propertyOptions);
					}
				}

				$data[$propertyName] = $propertyValue;
			}
		}

		return $data;
	}

	/**
	 * toJson -> ExtbaseCollection
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult|\TYPO3\CMS\Extbase\Persistence\ObjectStorage $collection
	 * @param array $options
	 * @return array
	 */
	public function extbaseCollectionToArray($collection, $options) {
		$data = [];

		if(empty($options) === true) {
			foreach($collection as $object) {
				if($object instanceof \TYPO3\CMS\Extbase\DomainObject\AbstractEntity || $object instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
					$data[] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($object, 'uid');
				}
			}
		} else {
			foreach($collection as $object) {
				if($object instanceof \TYPO3\CMS\Extbase\DomainObject\AbstractEntity || $object instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
					$uid = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($object, 'uid');
					$data[$uid] = $this->toArray($object, $options);

				} else {
					$data[] = $this->toArray($object, $options);
				}
			}
		}

		return $data;
	}

	/**
	 * toJson -> DateTime
	 * @param \DateTime $object
	 * @param array $options
	 * @return string
	 */
	public function datetimeToArray(\DateTime $object, $options) {
		return $object->format('c');
	}
}