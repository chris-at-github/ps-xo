<?php

namespace Ps\Xo\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class HtmlPolisher implements MiddlewareInterface {

	/**
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \Psr\Http\Server\RequestHandlerInterface $handler
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface {
		$response = $handler->handle($request);
		$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('xo');
		$allowedPageTypes = GeneralUtility::intExplode(',', $extensionConfiguration['htmlPolisherPageTypes']);
		$pageType = (int) GeneralUtility::_GP('type');

		if(empty($pageType) === true || in_array($pageType, $allowedPageTypes) === true) {
			if(!($response instanceof NullResponse) && $GLOBALS['TSFE'] instanceof TypoScriptFrontendController && $GLOBALS['TSFE']->isOutputting()) {

				$body = $response->getBody();
				$body->rewind();

				$html = $response->getBody()->getContents();
				$html = $this->removeEmptyAttributes($html);
				$html = $this->removeWhitespaces($html);
				$html = $this->removeDeprecatedHtml($html);

				return new HtmlResponse($html, $response->getStatusCode(), $response->getHeaders());
			}
		}

		return $response;
	}

	/**
	 * @param string $html
	 * @return string
	 */
	protected function removeEmptyAttributes($html) {

		// Entferne leere Klassen- und Title-Attribute
		$html = str_replace(' class=""', null, $html);
		$html = str_replace(' title=""', null, $html);

		return $html;
	}

	/**
	 * @param string $html
	 * @return string
	 */
	protected function removeDeprecatedHtml($html) {

		// kein type="text/css" und type="text/javascript"
		$html = str_replace([
			' type="text/css"',
			' type="text/javascript"'
		], null, $html);

		return $html;
	}

	/**
	 * @param string $html
	 * @return string $html
	 */
	protected function removeWhitespaces($html) {
		$applicationContext = \TYPO3\CMS\Core\Core\Environment::getContext();

		// Entferne alle Whitespaces zwischen den Tags
		if($applicationContext->isProduction() === true) {
			$html = trim(preg_replace('/\r|\n|\t/', ' ', $html));
			$html = preg_replace('/\\>\\s+\\</', '><', $html);
			$html = trim(preg_replace('/\s\s+/', ' ', $html));
		}

		return $html;
	}
}