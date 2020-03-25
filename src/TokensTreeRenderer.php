<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Tokenizer\Token\IToken;
use Mathematicator\Tokenizer\Token\SubToken;

final class TokensTreeRenderer
{

	/**
	 * @throws \Error
	 */
	public function __construct()
	{
		throw new \Error('Class ' . get_class($this) . ' is static and cannot be instantiated.');
	}


	/**
	 * Render simple tree view to HTML.
	 *
	 * @param IToken[] $tokens
	 * @param int $level
	 * @return string
	 */
	public static function render(array $tokens, int $level = 0): string
	{
		$return = '';

		foreach ($tokens as $token) {
			$return .= "\n" . str_repeat('&nbsp;&nbsp;&nbsp;', $level)
				. '<span style="color:#c22;background:#f5f5f5;padding:0 6px;border-radius:4px;">'
				. htmlspecialchars($token->getToken(), ENT_QUOTES)
				. '</span>&nbsp;→&nbsp;<span style="font-size:8pt"><span style="color:#aaa;">#'
				. htmlspecialchars((string) $token->getPosition(), ENT_QUOTES)
				. '</span> <span style="color:#3369c1;">'
				. htmlspecialchars($token->getType(), ENT_QUOTES)
				. '</span></span>';

			if ($token instanceof SubToken) {
				$return .= self::render($token->getTokens(), $level + 1);
				$return .= "\n" . str_repeat('&nbsp;&nbsp;&nbsp;', $level);
				$return .= '<span style="color:#c22;background:#f5f5f5;padding:0 6px;border-radius:4px;">)</span>';
			}
		}

		return $return;
	}
}
