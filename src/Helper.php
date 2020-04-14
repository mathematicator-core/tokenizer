<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Nette\Utils\Strings;

final class Helper
{
	/** @throws \Error */
	final public function __construct()
	{
		throw new \Error('Class ' . get_class($this) . ' is static and cannot be instantiated.');
	}


	/**
	 * @param string $roman
	 * @return int
	 */
	public static function romanToInt(string $roman): int
	{
		$romanNumber = ['m' => 1000000, 'd' => 500000, 'c' => 100000, 'l' => 50000, 'x' => 10000, 'v' => 5000, 'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
		$roman = Strings::upper($roman);
		$romanLength = Strings::length($roman);
		$return = 0;
		for ($i = 0; $i < $romanLength; $i++) {
			$x = $romanNumber[$roman[$i]];
			if ($i + 1 < \strlen($roman) && ($nextToken = $romanNumber[$roman[$i + 1]]) > $x) {
				$return += $nextToken - $x;
				$i++;
			} else {
				$return += $x;
			}
		}

		return $return;
	}
}