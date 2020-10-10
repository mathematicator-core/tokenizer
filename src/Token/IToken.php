<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


interface IToken
{
	public function getToken(): string;

	public function setToken(string $token): self;

	public function getPosition(): int;

	public function setPosition(int $position): self;

	public function getType(): string;

	public function setType(string $type): self;
}
