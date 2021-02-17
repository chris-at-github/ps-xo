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

		if(!($response instanceof NullResponse) && $GLOBALS['TSFE'] instanceof TypoScriptFrontendController && $GLOBALS['TSFE']->isOutputting()) {

			$body = $response->getBody();
			$body->rewind();

			$html = $response->getBody()->getContents();
			$html = $this->removeEmptyAttributes($html);
			$html = $this->removeWhitespaces($html);

			return new HtmlResponse($html, $response->getStatusCode(), $response->getHeaders());
		}

		return $response;
	}

	/**
	 * @param string $html
	 * @return string
	 */
	protected function removeEmptyAttributes($html) {

		// Entferne leere Klassen-Attribute
		$html = str_replace(' class=""', null, $html);

		return $html;
	}

	/**
	 * @param string $html
	 * @return string $html
	 */
	protected function removeWhitespaces($html) {

//		// Entferne alle Whitespaces zwischen den Tags
//		// @todo: nur im Produktion Mode
//		$html = trim(preg_replace('/\\>\\s+\\</', '><', $html));

		return $html;
	}
}