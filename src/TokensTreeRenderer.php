<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Tokenizer\Token\IToken;
use Mathematicator\Tokenizer\Token\SubToken;
use Nette\StaticClass;

class TokensTreeRenderer
{

	use StaticClass;

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
			$return .= "\n" . self::renderTabs($level)
				. '<span style="color:#c22;background:#f5f5f5;padding:0 6px;border-radius:4px;">'
				. htmlspecialchars($token->getToken())
				. '</span>&nbsp;&nbsp;&nbsp;→&nbsp;<span style="font-size:8pt"><span style="color:#aaa;">#'
				. htmlspecialchars((string) $token->getPosition())
				. '</span> <span style="color:#3369c1;">'
				. htmlspecialchars($token->getType())
				. '</span></span>';

			if ($token instanceof SubToken) {
				$return .= self::render($token->getTokens(), $level + 1);
				$return .= "\n" . self::renderTabs($level);
				$return .= '<span style="color:#c22;background:#f5f5f5;padding:0 6px;border-radius:4px;">)</span>';
			}
		}

		return $return;
	}

	/**
	 * @param int $count
	 * @return string
	 */
	private static function renderTabs(int $count = 1): string
	{
		$return = '';
		for ($i = 0; $i <= $count; $i++) {
			$return .= '&nbsp;&nbsp;&nbsp;';
		}

		return $return;
	}

}
