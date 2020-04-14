<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Engine\MathematicatorException;
use Mathematicator\Tokenizer\Token\IToken;
use Nette\Tokenizer\Exception;
use Nette\Tokenizer\Token;
use Nette\Tokenizer\Tokenizer as NetteTokenizer;

class Tokenizer
{

	/** @var NetteTokenizer */
	private $tokenizer;

	/** @var TokensToLatex */
	private $tokenToLatexTranslator;

	/** @var TokensToObject */
	private $tokensToObject;


	/**
	 * @param mixed[] $config
	 * @param TokensToLatex $tokenToLatexTranslator
	 * @param TokensToObject $tokensToObject
	 */
	public function __construct(array $config, TokensToLatex $tokenToLatexTranslator, TokensToObject $tokensToObject)
	{
		$this->tokenToLatexTranslator = $tokenToLatexTranslator;
		$this->tokensToObject = $tokensToObject;

		$this->tokenizer = new NetteTokenizer([
			Tokens::M_EQUATION => '\=+',
			Tokens::M_COMPARATOR => '<<|>>|<=>|<=|>=|===|!==|==|!=|<>|>|<',
			Tokens::M_FACTORIAL => '[0-9]*[.]?[0-9]+\!(?!\=)',
			Tokens::M_NUMBER => '(?<!\d)(?:(?:\-*[0-9]*[.]?[0-9]+)(?!\/)|[0-9]*[.]?[0-9]+)\d*',
			Tokens::M_INFINITY => 'INF',
			Tokens::M_PI => 'PI',
			Tokens::M_ROMAN_NUMBER => '[IVXLCDM]+',
			Tokens::M_VARIABLE => '[a-z]',
			Tokens::M_WHITESPACE => '\s+',
			Tokens::M_FUNCTION => implode('|', explode('|', implode('\(|', (array) $config['functions']) . '\(')),
			Tokens::M_STRING => '\w+',
			Tokens::M_OPERATOR => '[\+\-\*\/\^\!]',
			Tokens::M_LEFT_BRACKET => '\(',
			Tokens::M_RIGHT_BRACKET => '\)',
			Tokens::M_SEPARATOR => '[\,\;]+',
			Tokens::M_OTHER => '.+',
		]);
	}


	/**
	 * @param string $query
	 * @return Token[]
	 * @throws Exception
	 */
	public function tokenize(string $query): array
	{
		return $this->tokenizer->tokenize($query)->tokens;
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
			return $this->tokenToLatexTranslator->process($tokens);
		} catch (MathematicatorException $e) {
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
}
