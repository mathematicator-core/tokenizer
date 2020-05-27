<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Nette\SmartObject;

/**
 * @property string $token
 * @property int $position
 * @property string $type
 */
class BaseToken implements IToken
{
	use SmartObject;

	/** @var string */
	private $token;

	/** @var int */
	private $position;

	/** @var string */
	private $type;


	/**
	 * @return string
	 */
	public function getToken(): string
	{
		return $this->token;
	}


	/**
	 * @param string $token
	 * @return IToken
	 */
	public function setToken(string $token): IToken
	{
		$this->token = $token;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getPosition(): int
	{
		return $this->position ?? 0;
	}


	/**
	 * @param int $position
	 * @return IToken
	 */
	public function setPosition(int $position): IToken
	{
		$this->position = $position;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}


	/**
	 * @param string $type
	 * @return IToken
	 */
	public function setType(string $type): IToken
	{
		$this->type = $type;

		return $this;
	}


	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->getToken();
	}
}
