<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Tokenizer\Exceptions\TokenizerException;
use Mathematicator\Tokenizer\Token\IToken;
use Nette\Tokenizer\Exception;
use Nette\Tokenizer\Token;
use Nette\Tokenizer\Tokenizer as NetteTokenizer;

class Tokenizer
{

	/** @var TokensToLatex */
	private $tokenToLatexTranslator;

	/** @var TokensToObject */
	private $tokensToObject;

	/** @var FunctionManager|null */
	private $functionManager;


	/**
	 * @param TokensToLatex $tokenToLatexTranslator
	 * @param TokensToObject $tokensToObject
	 */
	public function __construct(TokensToLatex $tokenToLatexTranslator, TokensToObject $tokensToObject)
	{
		$this->tokenToLatexTranslator = $tokenToLatexTranslator;
		$this->tokensToObject = $tokensToObject;
	}


	/**
	 * @return string[]
	 */
	public function getFunctionNames(): array
	{
		if ($this->functionManager === null) {
			return [];
		}

		return $this->functionManager->getFunctionNames();
	}


	/**
	 * @return FunctionManager|null
	 */
	public function getFunctionManager(): ?FunctionManager
	{
		return $this->functionManager;
	}


	/**
	 * @param FunctionManager $functionManager
	 */
	public function setFunctionManager(FunctionManager $functionManager): void
	{
		$this->functionManager = $functionManager;
	}


	/**
	 * @param string $query
	 * @return Token[]
	 * @throws Exception
	 */
	public function tokenize(string $query): array
	{
		return $this->getTokenizer()->tokenize($query)->tokens;
	}


	/**
	 * @param Token[] $tokens
	 * @return IToken[]
	 */
	public function tokensToObject(array $tokens): array
	{
		return $this->tokensToObject->toObject($tokens);
	}


	/**
	 * @param IToken[] $tokens
	 * @return string
	 */
	public function tokensToLatex(array $tokens): string
	{
		try {
			return $this->tokenToLatexTranslator->render($tokens);
		} catch (TokenizerException $e) {
			return '';
		}
	}


	/**
	 * Method return debug tree as HTML string.
	 *
	 * @param IToken[] $tokens
	 * @return string
	 */
	public function renderTokensTree(array $tokens): string
	{
		return '<pre>' . TokensTreeRenderer::render($tokens) . '</pre>';
	}


	private function getTokenizer(): NetteTokenizer
	{
		return new NetteTokenizer([
			Tokens::M_EQUATION => '\=+',
			Tokens::M_COMPARATOR => '<<|>>|<=>|<=|>=|===|!==|==|!=|<>|>|<',
			Tokens::M_FACTORIAL => '[0-9]*[.]?[0-9]+\!(?!\=)',
			Tokens::M_NUMBER => '(?<!\d)(?:(?:\-*[0-9]*[.]?[0-9]+)(?!\/)|[0-9]*[.]?[0-9]+)\d*',
			Tokens::M_INFINITY => 'INF',
			Tokens::M_PI => 'PI',
			Tokens::M_ROMAN_NUMBER => '[IVXLCDM]+',
			Tokens::M_VARIABLE => '[a-z]',
			Tokens::M_WHITESPACE => '\s+',
			Tokens::M_FUNCTION => implode('|', explode('|', implode('\(|', $this->getFunctionNames()) . '\(')),
			Tokens::M_STRING => '\w+',
			Tokens::M_OPERATOR => '[\+\-\*\/\^\!]',
			Tokens::M_LEFT_BRACKET => '\(',
			Tokens::M_RIGHT_BRACKET => '\)',
			Tokens::M_SEPARATOR => '[\,\;]+',
			Tokens::M_OTHER => '.+',
		]);
	}
}
