<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Numbers\Latex\MathLatexBuilder;
use Mathematicator\Numbers\Latex\MathLatexToolkit;
use Mathematicator\Tokenizer\Exceptions\TokenizerException;
use Mathematicator\Tokenizer\Token\ComparatorToken;
use Mathematicator\Tokenizer\Token\FunctionToken;
use Mathematicator\Tokenizer\Token\IToken;
use Mathematicator\Tokenizer\Token\OperatorToken;
use Mathematicator\Tokenizer\Token\PolynomialToken;
use Mathematicator\Tokenizer\Token\RomanNumberToken;
use Mathematicator\Tokenizer\Token\SubToken;
use Mathematicator\Tokenizer\Token\VariableToken;
use Nette\Utils\Strings;

final class TokensToLatex
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

	/** @var bool[] */
	private $noBracketFunctions = [
		'sqrt' => true,
	];


	public function __construct(FunctionManagerFacade $functionManager)
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
			'\\\(' . implode('|', $functionManager->getFunctionNames()) . ')\{\\\left\(([^\(\)]+?)\\\right\)\}' => '\\\$1{$2}',
			'([+-]?[0-9]*[.]?[0-9]+)[eE]([+-]?[0-9]*[.]?[0-9]+)' => '{$1}^{$2}',
		];
	}


	/**
	 * @param IToken[] $tokens
	 * @return string
	 * @throws TokenizerException
	 */
	public function render(array $tokens): string
	{
		return $this->iterator($tokens);
	}


	/**
	 * @param IToken[] $tokens
	 * @param int $level
	 * @return string
	 * @throws TokenizerException
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
					if (isset($this->noBracketFunctions[$token->getName()]) === false) {
						$latex .= $this->getLeftBracket($level);
					}
					$latex .= $this->iterator($token->getTokens(), $level + 1);
					if (isset($this->noBracketFunctions[$token->getName()]) === false) {
						$latex .= $this->getRightBracket($level);
					}
					$latex .= '}';
				} elseif ($token instanceof SubToken) {
					$latex .= $this->getLeftBracket($level);
					$latex .= $this->iterator($token->getTokens(), $level + 1);
					$latex .= $this->getRightBracket($level);
				}
			} elseif ($isFunc === true) {
				$latex .= $this->latexTranslateTable((string) preg_replace('/\(+$/', '', (string) $tk));
			} elseif (($isOperator = $token instanceof OperatorToken) && $tk === '/') { // Fraction x/y
				$latex .= $this->renderFraction($iterator, $level);
			} elseif (($isOperator === true && $tk === '^') || ($next !== null && $nextTk === '^')) { // Pow x^y
				if ($token instanceof OperatorToken) {
					$latex .= $this->renderPow($iterator, $level);
				}
			} elseif ($token instanceof ComparatorToken) { // Comparator x=y
				$latex .= $this->latexTranslateTable((string) $tk);
			} elseif ($token instanceof RomanNumberToken) { // Roman number XVII
				$latex .= '\textrm{' . $tk . '}';
			} elseif ($token instanceof PolynomialToken) {
				$latex .= ($token->getTimes()->getToken() === '1' ? '' : $token->getTimes()->getNumber()->toLatex())
					. ($token->getPower()->getToken() === '1'
						? $token->getVariable()->getToken()
						: (string) MathLatexToolkit::pow(
							$token->getVariable()->getToken(),
							$token->getPower()->getNumber()->toLatex()
						)
					);
			} elseif ($token instanceof VariableToken) { // Variable (e.g. x)
				if ($next === null || ($nextTk !== '/' && $nextTk !== '^')) {
					$latex .= (!$token->getTimes()->isInteger() || !$token->getTimes()->toBigInteger()->isEqualTo(1)
							? $token->getTimes()->toLatex()
							: ''
						) . $tk;
				}
			} elseif ($token !== null) { // Other tokens (e.g. NumberToken)
				if ($next === null || ($nextTk !== '/' && $nextTk !== '^')) {
					$latex .= $this->latexTranslateTable((string) $tk);
				}
			}

			$iterator->next();
		} while (!$iterator->isFinal());

		return $this->processReplaceTable(
			$this->processReplaceTable($latex, $this->beforeReplaceTable),
			$this->afterReplaceTable
		);
	}


	private function latexTranslateTable(string $token): string
	{
		return self::$charTable[$token] ?? $token;
	}


	private function getLeftBracket(int $level): string
	{
		return ['\\left(', '\\left[', '\\left\\{'][$level % 3];
	}


	private function getRightBracket(int $level): string
	{
		return ['\\right)', '\\right]', '\\right\\}'][$level % 3];
	}


	/**
	 * @throws TokenizerException
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

		return (string) MathLatexToolkit::frac($lastTokenRender, $nextTokenRender);
	}


	/**
	 * @throws TokenizerException
	 */
	private function renderPow(TokenIterator $iterator, int $level): MathLatexBuilder
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

		return MathLatexToolkit::pow($downTokenRender, $topTokenRender);
	}


	/**
	 * Fix generated haystack by smart regular patterns.
	 *
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
