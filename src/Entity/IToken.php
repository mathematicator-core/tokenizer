<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


interface IToken
{

	/**
	 * @return string
	 */
	public function getToken(): string;

	/**
	 * @param string $token
	 * @return IToken
	 */
	public function setToken(string $token): IToken;

	/**
	 * @return int
	 */
	public function getPosition(): int;

	/**
	 * @param int $position
	 * @return IToken
	 */
	public function setPosition(int $position): IToken;

	/**
	 * @return string
	 */
	public function getType(): string;

	/**
	 * @param string $type
	 * @return IToken
	 */
	public function setType(string $type): IToken;

}
