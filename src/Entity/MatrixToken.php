<?php

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Engine\MathErrorException;
use Mathematicator\Numbers\SmartNumber;

class MatrixToken extends BaseToken
{

	/**
	 * @var Number[][]
	 */
	private $matrix;

	/**
	 * MatrixToken constructor.
	 *
	 * @param Number[][] $matrix
	 */
	public function __construct(array $matrix)
	{
		$this->matrix = $this->validator($matrix);
	}

	/**
	 * @return Number[][]
	 */
	public function getMatrix(): array
	{
		return $this->matrix;
	}

	/**
	 * @param Number[][] $matrix
	 */
	public function setMatrix(array $matrix): void
	{
		$this->matrix = $this->validator($matrix);
	}

	/**
	 * @param Number[][] $matrix
	 * @return Number[][]
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
				throw new MathErrorException('Matrix is invaliid structure. Can\'t have spaces in array.');
			}

			foreach ($row as $col) {
				if (!$col instanceof SmartNumber) {
					throw new MathErrorException('All matrix items must be number.');
				}
			}
		}

		return $matrix;
	}

}