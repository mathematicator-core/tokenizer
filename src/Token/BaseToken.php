<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


abstract class BaseToken implements IToken
{
	private string $token;

	private int $position;

	private string $type;


	public function getToken(): string
	{
		return $this->token;
	}


	public function setToken(string $token): IToken
	{
		$this->token = $token;

		return $this;
	}


	public function getPosition(): int
	{
		return $this->position ?? 0;
	}


	public function setPosition(int $position): IToken
	{
		$this->position = $position;

		return $this;
	}


	public function getType(): string
	{
		return $this->type;
	}


	public function setType(string $type): IToken
	{
		$this->type = $type;

		return $this;
	}


	public function __toString(): string
	{
		return $this->getToken();
	}
}
