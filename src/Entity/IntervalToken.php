<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


class IntervalToken extends BaseToken
{
	public const TYPE_OPEN = 'open';

	public const TYPE_CLOSED = 'closed';

	/** @var IToken */
	private $from;

	/** @var string */
	private $fromType;

	/** @var IToken */
	private $to;

	/** @var string */
	private $toType;


	/**
	 * @param IToken $from
	 * @param IToken $to
	 * @param string $fromType
	 * @param string $toType
	 */
	public function __construct(IToken $from, IToken $to, string $fromType = self::TYPE_OPEN, string $toType = self::TYPE_OPEN)
	{
		$this->from = $from;
		$this->to = $to;
		$this->fromType = $fromType;
		$this->toType = $toType;
	}


	/**
	 * @return IToken
	 */
	public function getFrom(): IToken
	{
		return $this->from;
	}


	/**
	 * @return IToken
	 */
	public function getTo(): IToken
	{
		return $this->to;
	}


	/**
	 * @return string
	 */
	public function getFromType(): string
	{
		return $this->fromType;
	}


	/**
	 * @return string
	 */
	public function getToType(): string
	{
		return $this->toType;
	}
}