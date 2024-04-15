<?php

namespace ChessInterview\Figure;

class Pawn extends Figure
{
    private bool $hasMoved = false;

    private const NUMBER_X_STEPS_TO_MOVE_FIRST_TIME = 2;

    public function __toString()
    {
        return $this->isBlack ? '♟' : '♙';
    }

    public function isCorrectMove(string $xFrom, int $yFrom, string $xTo, int $yTo, bool $hasOpponentFigure, bool $isPathClear): bool
    {
        $direction = $this->getDirection();
        $xDifference = ord($xTo) - ord($xFrom);
        $yDifference = $yTo - $yFrom;

        if ($xTo === $xFrom) {
            if ($this->hasMoved) {
                return $yDifference === $direction;
            } else {
                return $yDifference === $direction || ($yDifference === self::NUMBER_X_STEPS_TO_MOVE_FIRST_TIME * $direction && $isPathClear);
            }
        }

        if (abs($xDifference) === 1 && abs($yDifference) === 1) {
            if ($hasOpponentFigure) {
                return true;
            }
        }

        return false;
    }

    public function setMove(): void
    {
        $this->hasMoved = true;
    }

    private function getDirection(): int
    {
        return $this->isBlack ? -1 : 1;
    }
}
