<?php

namespace Ps\Xo\Evaluation;

class FloatEvaluation {

	/**
	 * JavaScript code for client side validation/evaluation
	 *
	 * @see: https://stackoverflow.com/a/61679717
	 * @return string JavaScript code for client side validation/evaluation
	 */
	public function returnFieldJS() {
		return 'return value.replace(/[^\d\-.,]/g, "").replace(",", ".").replace(/\.(?=.*\.)/g, "");';
	}

	/**
	 * Server-side validation/evaluation on saving the record
	 *
	 * @param string $value The field value to be evaluated
	 * @param string $is_in The "is_in" value of the field configuration from TCA
	 * @param bool $set Boolean defining if the value is written to the database or not.
	 * @return string Evaluated field value
	 */
	public function evaluateFieldValue($value, $is_in, &$set) {
		return (float) $value;
	}

	/**
	 * Server-side validation/evaluation on opening the record
	 *
	 * @param array $parameters Array with key 'value' containing the field value from the database
	 * @return string Evaluated field value
	 */
	public function deevaluateFieldValue(array $parameters) {
		return (float)$parameters['value'];
	}
}