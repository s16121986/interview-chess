<?php

namespace ChessInterview\Figure;

class Queen extends Figure
{
    public function __toString()
    {
        return $this->isBlack ? '♛' : '♕';
    }

    public function isCorrectMove(string $xFrom, int $yFrom, string $xTo, int $yTo, bool $hasOpponentFigure, bool $isPathClear): bool
    {
        return true;
    }
}
