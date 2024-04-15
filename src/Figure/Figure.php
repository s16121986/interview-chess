<?php

namespace ChessInterview\Figure;

abstract class Figure
{
    protected bool $isBlack;

    public function __construct(bool $isBlack)
    {
        $this->isBlack = $isBlack;
    }

    public function isBlack(): bool
    {
        return $this->isBlack;
    }

    abstract public function isCorrectMove(string $xFrom, int $yFrom, string $xTo, int $yTo, bool $hasOpponentFigure, bool $isPathClear): bool;
}
