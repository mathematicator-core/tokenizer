<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Engine\MathematicatorException;
use Mathematicator\Tokenizer\Token\ComparatorToken;
use Mathematicator\Tokenizer\Token\FunctionToken;
use Mathematicator\Tokenizer\Token\IToken;
use Mathematicator\Tokenizer\Token\OperatorToken;
use Mathematicator\Tokenizer\Token\PolynomialToken;
use Mathematicator\Tokenizer\Token\RomanNumberToken;
use Mathematicator\Tokenizer\Token\SubToken;
use Mathematicator\Tokenizer\Token\VariableToken;
use Nette\Utils\Strings;

class TokensToLatex
{

	/** @var string[] */
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

	/** @var string[] */
	private $beforeReplaceTable;

	/** @var string[] */
	private $afterReplaceTable;

	/** @var string[] */
	private $noBracketFunctions = [
		'sqrt',
	];


	/**
	 * @param string[] $functions
	 */
	public function __construct(array $functions)
	{
		$this->beforeReplaceTable = [
			'INF' => '\\infty',
			'PI' => '\\pi',
		];

		$this->afterReplaceTable = [
			'log(\d+)' => '\log_{$1}',
			'\*' => '\cdot ',
			'(\d)\\\cdot\s*([a-z])' => '$1$2',
			'abs\\\left[\(\[\{](.+?)\\\right[\)\]\}]' => '\mid $1 \mid',
			'\\\(' . implode('|', $functions) . ')\{\\\left\(([^\(\)]+?)\\\right\)\}' => '\\\$1{$2}',
			'([+-]?[0-9]*[.]?[0-9]+)[eE]([+-]?[0-9]*[.]?[0-9]+)' => '{$1}^{$2}',
		];
	}


	/**
	 * @param IToken[] $tokens
	 * @return string
	 * @throws MathematicatorException
	 */
	public function process(array $tokens): string
	{
		return $this->iterator($tokens);
	}


	/**
	 * @param IToken[] $tokens
	 * @param int $level
	 * @return string
	 * @throws MathematicatorException
	 */
	private function iterator(array $tokens, int $level = 0): string
	{
		$latex = '';
		$iterator = new TokenIterator($tokens);

		do {
			$tk = ($token = $iterator->getToken()) === null ? null : $token->getToken();
			$nextTk = ($next = $iterator->getNextToken()) === null ? null : $next->getToken();
			$isFunc = $token instanceof FunctionToken;
			if (($isFunc === true || $token instanceof SubToken) && ($next === null || ($nextTk !== '/' && $nextTk !== '^'))) {
				// Function or sub token (fraction, etc...)
				if ($token instanceof FunctionToken) {
					$latex .= $this->latexTranslateTable($token->getName());
					$latex .= '{';
					if (\in_array($token->getName(), $this->noBracketFunctions, true) === false) {
						$latex .= $this->getLeftBracket($level);
					}
					$latex .= $this->iterator($token->getTokens(), $level + 1);
					if (\in_array($token->getName(), $this->noBracketFunctions, true) === false) {
						$latex .= $this->getRightBracket($level);
					}
					$latex .= '}';
				} elseif ($token instanceof SubToken) {
					$latex .= $this->getLeftBracket($level);
					$latex .= $this->iterator($token->getTokens(), $level + 1);
					$latex .= $this->getRightBracket($level);
				}
			} elseif ($isFunc === true) {
				$latex .= $this->latexTranslateTable((string) preg_replace('/\(+$/', '', $tk));
			} elseif (($isOperator = $token instanceof OperatorToken) && $tk === '/') { // Fraction x/y
				$latex .= $this->renderFraction($iterator, $level);
			} elseif (($isOperator === true && $tk === '^') || ($next !== null && $nextTk === '^')) { // Pow x^y
				if ($token instanceof OperatorToken) {
					$latex .= $this->renderPow($iterator, $level);
				}
			} elseif ($token instanceof ComparatorToken) { // Comparator x=y
				$latex .= $this->latexTranslateTable($tk);
			} elseif ($token instanceof RomanNumberToken) { // Roman number XVII
				$latex .= '\textrm{' . $tk . '}';
			} elseif ($token instanceof PolynomialToken) {
				$latex .= ($token->getTimes()->getToken() === '1' ? '' : $token->getTimes()->getNumber()->getString())
					. ($token->getPower()->getToken() === '1'
						? $token->getVariable()->getToken()
						: '{' . $token->getVariable()->getToken() . '}'
						. '^{' . $token->getPower()->getNumber()->getString() . '}'
					);
			} elseif ($token instanceof VariableToken) { // Variable "x"
				$latex .= ($token->getTimes()->isInteger() === false || $token->getTimes()->getInteger() !== '1'
						? $token->getTimes()->getString()
						: ''
					) . $tk;
			} elseif ($token !== null) { // Other tokens
				if ($next === null || ($nextTk !== '/' && $nextTk !== '^')) {
					$latex .= $this->latexTranslateTable($tk);
				}
			}

			$iterator->next();
		} while ($iterator->isFinal() === false);

		return $this->processReplaceTable(
			$this->processReplaceTable($latex, $this->beforeReplaceTable),
			$this->afterReplaceTable
		);
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
	 * @throws MathematicatorException
	 */
	private function renderFraction(TokenIterator $iterator, int $level): string
	{
		$lastToken = $iterator->getLastToken();
		$nextToken = $iterator->getNextToken();
		$iterator->next();

		if ($lastToken instanceof SubToken) {
			$lastTokenRender = $this->iterator($lastToken->getTokens(), $level);
		} else {
			$lastTokenRender = $lastToken === null ? '?' : $lastToken->getToken();
		}

		if ($nextToken instanceof SubToken) {
			$nextTokenRender = $this->iterator($nextToken->getTokens(), $level);
		} else {
			$nextTokenRender = $nextToken === null ? '?' : $nextToken->getToken();
		}

		return '\frac{' . $lastTokenRender . '}{' . $nextTokenRender . '}';
	}


	/**
	 * @param TokenIterator $iterator
	 * @param int $level
	 * @return string
	 * @throws MathematicatorException
	 */
	private function renderPow(TokenIterator $iterator, int $level): string
	{
		$lastToken = $iterator->getLastToken();
		$nextToken = $iterator->getNextToken();
		$iterator->next();

		if ($lastToken instanceof SubToken) {
			$downTokenRender = $this->getLeftBracket($level)
				. $this->iterator($lastToken->getTokens(), $level)
				. $this->getRightBracket($level);
		} else {
			$downTokenRender = $lastToken === null ? '?' : $lastToken->getToken();
		}

		if ($nextToken instanceof SubToken) {
			$topTokenRender = $this->getLeftBracket($level)
				. $this->iterator($nextToken->getTokens(), $level)
				. $this->getRightBracket($level);
		} else {
			$topTokenRender = $nextToken === null ? '?' : $nextToken->getToken();
		}

		return '{' . $downTokenRender . '}^{' . $topTokenRender . '}';
	}


	/**
	 * Fix generated haystack by smart regular patterns.
	 *
	 * @param string $haystack
	 * @param string[] $replaceTable
	 * @return string
	 */
	private function processReplaceTable(string $haystack, array $replaceTable): string
	{
		foreach ($replaceTable as $key => $value) {
			$haystack = Strings::replace($haystack, '/' . $key . '/', $value);
		}

		return $haystack;
	}
}
