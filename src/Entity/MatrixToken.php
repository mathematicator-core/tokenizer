<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Engine\MathErrorException;
use Mathematicator\Numbers\SmartNumber;

class MatrixToken extends BaseToken
{

	/** @var SmartNumber[][] */
	private $matrix;


	/**
	 * @param SmartNumber[][] $matrix
	 * @throws MathErrorException
	 */
	public function __construct(array $matrix)
	{
		$this->matrix = $this->validator($matrix);
	}


	/**
	 * @return SmartNumber[][]
	 */
	public function getMatrix(): array
	{
		return $this->matrix;
	}


	/**
	 * @param SmartNumber[][] $matrix
	 * @throws MathErrorException
	 */
	public function setMatrix(array $matrix): void
	{
		$this->matrix = $this->validator($matrix);
	}


	/**
	 * @param SmartNumber[][] $matrix
	 * @return SmartNumber[][]
	 * @throws MathErrorException
	 */
	private function validator(array $matrix): array
	{
		$lastCols = null;

		foreach ($matrix as $row) {
			$cols = \count($row);

			if ($lastCols === null) {
				$lastCols = $cols;
			} elseif ($cols !== $lastCols) {
				throw new MathErrorException('Matrix structure is invalid: Array can not contain empty space.');
			}

			foreach ($row as $col) {
				if (!$col instanceof SmartNumber) {
					throw new MathErrorException('All matrix items must be a number.');
				}
			}
		}

		return $matrix;
	}
}