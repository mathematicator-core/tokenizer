<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Nette\SmartObject;

/**
 * @property string $token
 * @property int $position
 * @property string $type
 */
abstract class BaseToken implements IToken
{
	use SmartObject;

	/** @var string */
	private $token;

	/** @var int */
	private $position;

	/** @var string */
	private $type;


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
