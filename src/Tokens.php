<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


class Tokens
{

	public const M_NUMBER = 'number';
	public const M_ROMAN_NUMBER = 'number_roman';
	public const M_VARIABLE = 'variable';
	public const M_FACTORIAL = 'factorial';
	public const M_INFINITY = 'infinity';
	public const M_WHITESPACE = 'whitespace';
	public const M_FUNCTION = 'function';
	public const M_STRING = 'string';
	public const M_OPERATOR = 'operator';
	public const M_LEFT_BRACKET = 'left_bracket';
	public const M_RIGHT_BRACKET = 'right_bracket';
	public const M_EQUATION = 'equation';
	public const M_COMPARATOR = 'comparator';
	public const M_SEPARATOR = 'separator';
	public const M_CONSTANT = 'constant';
	public const M_PI = 'pi';
	public const M_OTHER = 'other';

}
