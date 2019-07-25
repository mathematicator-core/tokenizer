<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Engine\MathematicatorException;
use Mathematicator\Tokenizer\Token\BaseToken;
use Mathematicator\Tokenizer\Token\ComparatorToken;
use Mathematicator\Tokenizer\Token\FunctionToken;
use Mathematicator\Tokenizer\Token\IToken;
use Mathematicator\Tokenizer\Token\OperatorToken;
use Mathematicator\Tokenizer\Token\RomanNumberToken;
use Mathematicator\Tokenizer\Token\SubToken;
use Mathematicator\Tokenizer\Token\VariableToken;
use Nette\Utils\Strings;

class TokensToLatex
{

	/**
	 * @var string[]
	 */
	private static $charTable = [
		'sin' => '\\sin',
		'cos' => '\\cos',
		'tan' => '\\tan',
		'sqrt' => '\\sqrt',
		'log' => '\\log',
		'ln' => '\\ln',
		'<<' => '\ \lt\ ',
		'>>' => '\ \gt\ ',
		'<=>' => '\ \not =\ ',
		'<=' => '\ \le\ ',
		'>=' => '\ \ge\ ',
		'===' => '\ =\ ',
		'!==' => '\ \not =\ ',
		'==' => '\ =\ ',
		'!=' => '\ \not =\ ',
		'<>' => '\ \not =\ ',
		'>' => '\ \gt\ ',
		'<' => '\ \lt\ ',
	];

	/**
	 * @var string[]
	 */
	private $afterReplaceTable;

	/**
	 * @var string[]
	 */
	private $noBracketFunctions = [
		'sqrt',
	];

	/**
	 * @param string[] $functions
	 */
	public function __construct(array $functions)
	{
		$this->afterReplaceTable = [
			'log(\d+)' => '\log_{$1}',
			'\*' => '\cdot ',
			'(\d)\\\cdot\s*([a-z])' => '$1$2',
			'abs\\\left[\(\[\{](.+?)\\\right[\)\]\}]' => '\mid $1 \mid',
			'\\\(' . implode('|', $functions) . ')\{\\\left\(([^\(\)]+?)\\\right\)\}' => '\\\$1{$2}',
			'INF' => '\\infty',
			'PI' => '\\pi',
		];
	}

	/**
	 * @param IToken[] $tokens
	 * @return string
	 * @throws MathematicatorException
	 */
	public function process(array $tokens): string
	{
		return $this->iterator($tokens, 0);
	}

	/**
	 * @param IToken[] $tokens
	 * @param int $level
	 * @return string
	 * @throws MathematicatorException
	 */
	private function iterator(array $tokens, int $level): string
	{
		$latex = '';
		$iterator = new TokenIterator($tokens);

		while (true) {
			/** @var BaseToken $token */
			$token = $iterator->getToken();
			$isSubToken = $token instanceof SubToken;
			$isFunctionToken = $token instanceof FunctionToken;

			if (
				($isSubToken || $isFunctionToken)
				&& ($iterator->getNextToken() === null
					|| (
						$iterator->getNextToken()->getToken() !== '/'
						&& $iterator->getNextToken()->getToken() !== '^'
					)
				)
			) {
				if ($token instanceof FunctionToken) {
					$latex .= $this->latexTranslateTable($token->getName());
					$latex .= '{';
					if (!\in_array($token->getName(), $this->noBracketFunctions)) {
						$latex .= $this->getLeftBracket($level);
					}
					$latex .= $this->iterator($token->getTokens(), $level + 1);
					if (!\in_array($token->getName(), $this->noBracketFunctions)) {
						$latex .= $this->getRightBracket($level);
					}
					$latex .= '}';
				} elseif ($token instanceof SubToken) {
					$latex .= $this->getLeftBracket($level);
					$latex .= $this->iterator($token->getTokens(), $level + 1);
					$latex .= $this->getRightBracket($level);
				}
			} elseif ($isFunctionToken) {
				$latex .= $this->latexTranslateTable(
					preg_replace('/\(+$/', '', $token->getToken())
				);
			} elseif ($token instanceof OperatorToken && $token->getToken() === '/') {
				$latex .= $this->renderFraction($iterator, $level);
			} elseif ($token instanceof OperatorToken && $token->getToken() === '^') {
				$latex .= $this->renderPow($iterator, $level);
			} elseif ($token instanceof ComparatorToken) {
				$latex .= $this->latexTranslateTable($token->getToken());
			} elseif ($token instanceof RomanNumberToken) {
				$latex .= '\textrm{' . $token->getToken() . '}';
			} elseif ($token instanceof VariableToken) {
				$latex .= ($token->getTimes()->isInteger() === false || $token->getTimes()->getInteger() !== '1'
						? $token->getTimes()->getString()
						: ''
					) . $token->getToken();
			} elseif ($token) {
				if ($iterator->getNextToken() === null
					|| (
						$iterator->getNextToken()->getToken() !== '/'
						&& $iterator->getNextToken()->getToken() !== '^'
					)
				) {
					$latex .= $this->latexTranslateTable($token->getToken());
				}
			}

			$iterator->next();
			if ($iterator->isFinal()) {
				break;
			}
		}

		return $this->afterReplaceTable($latex);
	}

	/**
	 * @param string $token
	 * @return string
	 */
	private function latexTranslateTable(string $token): string
	{
		return self::$charTable[$token] ?? $token;
	}

	/**
	 * @param int $level
	 * @return string
	 */
	private function getLeftBracket(int $level): string
	{
		return ['\\left(', '\\left[', '\\left\\{'][$level % 3];
	}

	/**
	 * @param int $level
	 * @return string
	 */
	private function getRightBracket(int $level): string
	{
		return ['\\right)', '\\right]', '\\right\\}'][$level % 3];
	}

	/**
	 * @param TokenIterator $iterator
	 * @param int $level
	 * @return string
	 */
	private function renderFraction(TokenIterator $iterator, int $level): string
	{
		$lastToken = $iterator->getLastToken();
		$nextToken = $iterator->getNextToken();
		$iterator->next();

		if ($lastToken instanceof SubToken) {
			$_lastToken = $this->iterator($lastToken->getTokens(), $level);
		} else {
			$_lastToken = $lastToken->getToken();
		}

		if ($nextToken instanceof SubToken) {
			$_nextToken = $this->iterator($nextToken->getTokens(), $level);
		} else {
			$_nextToken = $nextToken ? $nextToken->getToken() : '?';
		}

		return '\frac{' . $_lastToken . '}{' . $_nextToken . '}';
	}

	/**
	 * @param TokenIterator $iterator
	 * @param int $level
	 * @return string
	 */
	private function renderPow(TokenIterator $iterator, int $level): string
	{
		$lastToken = $iterator->getLastToken();
		$nextToken = $iterator->getNextToken();
		$iterator->next();

		if ($lastToken instanceof SubToken) {
			$_lastToken = $this->getLeftBracket($level)
				. $this->iterator($lastToken->getTokens(), $level)
				. $this->getRightBracket($level);
		} else {
			$_lastToken = $lastToken->getToken();
		}

		if ($nextToken instanceof SubToken) {
			$_nextToken = $this->getLeftBracket($level)
				. $this->iterator($nextToken->getTokens(), $level)
				. $this->getRightBracket($level);
		} else {
			$_nextToken = $nextToken ? $nextToken->getToken() : '?';
		}

		return '{' . $_lastToken . '}^{' . $_nextToken . '}';
	}

	/**
	 * @param string $latex
	 * @return string
	 */
	private function afterReplaceTable(string $latex): string
	{
		foreach ($this->afterReplaceTable as $key => $value) {
			$latex = Strings::replace($latex, '/' . $key . '/', $value);
		}

		return $latex;
	}

}
