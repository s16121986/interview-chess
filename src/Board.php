<?php

declare(strict_types=1);

namespace ChessInterview;

use ChessInterview\Exception\MoveException;
use ChessInterview\Figure\Bishop;
use ChessInterview\Figure\King;
use ChessInterview\Figure\Knight;
use ChessInterview\Figure\Pawn;
use ChessInterview\Figure\Queen;
use ChessInterview\Figure\Rook;

class Board {
    private array $figures = [];
    private bool $isLastMoveBlackFigure = true;

    public function __construct() {
        $this->initializeFigures();
    }

    private function initializeFigures(): void
    {
        $this->initializePawns();
        $this->initializeRooks();
        $this->initializeKnights();
        $this->initializeBishops();
        $this->initializeQueens();
        $this->initializeKings();
    }

    private function initializePawns(): void
    {
        for ($x = 'a'; $x <= 'h'; $x++) {
            $this->figures[$x][2] = new Pawn(false);
            $this->figures[$x][7] = new Pawn(true);
        }
    }

    private function initializeRooks(): void
    {
        $this->figures['a'][1] = new Rook(false);
        $this->figures['h'][1] = new Rook(false);
        $this->figures['a'][8] = new Rook(true);
        $this->figures['h'][8] = new Rook(true);
    }

    private function initializeKnights(): void
    {
        $this->figures['b'][1] = new Knight(false);
        $this->figures['g'][1] = new Knight(false);
        $this->figures['b'][8] = new Knight(true);
        $this->figures['g'][8] = new Knight(true);
    }

    private function initializeBishops(): void
    {
        $this->figures['c'][1] = new Bishop(false);
        $this->figures['f'][1] = new Bishop(false);
        $this->figures['c'][8] = new Bishop(true);
        $this->figures['f'][8] = new Bishop(true);
    }

    private function initializeKings(): void
    {
        $this->figures['e'][1] = new King(false);
        $this->figures['e'][8] = new King(true);
    }

    private function initializeQueens(): void
    {
        $this->figures['d'][1] = new Queen(false);
        $this->figures['d'][8] = new Queen(true);
    }

    public function move($move): void
    {
        if (!preg_match('/^([a-h])(\d)-([a-h])(\d)$/', $move, $match)) {
            throw new MoveException("Incorrect move");
        }

        $xFrom = $match[1];
        $yFrom = (int) $match[2];
        $xTo   = $match[3];
        $yTo   = (int) $match[4];

        $hasOpponentFigure = $this->hasOpponentFigure($xTo, $yTo);
        $isPathClear = $this->isPathClear($xFrom, $yFrom, $xTo, $yTo);

        if (isset($this->figures[$xFrom][$yFrom])) {
            if ($this->figures[$xFrom][$yFrom]->isBlack() === $this->isLastMoveBlackFigure) {
                throw new MoveException("It's not your turn");
            }
            if (! $this->figures[$xFrom][$yFrom]->isCorrectMove($xFrom, $yFrom, $xTo, $yTo, $hasOpponentFigure, $isPathClear)) {
                throw new MoveException('not correct move');
            }

            $this->figures[$xFrom][$yFrom]->setMove();

            $this->figures[$xTo][$yTo] = $this->figures[$xFrom][$yFrom];
            $this->isLastMoveBlackFigure = $this->figures[$xFrom][$yFrom]->isBlack();
        }
    }

    public function view(): void
    {
        for ($y = 8; $y >= 1; $y--) {
            echo "$y ";
            for ($x = 'a'; $x <= 'h'; $x++) {
                if (isset($this->figures[$x][$y])) {
                    echo $this->figures[$x][$y];
                } else {
                    echo '-';
                }
            }
            echo "\n";
        }
        echo "  abcdefgh\n";
    }

    private function hasOpponentFigure(string $xTo, int $yTo): bool
    {
        if (!isset($this->figures[$xTo][$yTo])) {
            return false;
        }

        if ($this->figures[$xTo][$yTo]->isBlack() === $this->isLastMoveBlackFigure) {
            return true;
        }

        return false;
    }

    private function isPathClear(string $xFrom, int $yFrom, string $xTo, int $yTo): bool
    {
        if ($xFrom === $xTo && abs($yTo - $yFrom) === 2) {
            $yMiddle = ($yFrom + $yTo) / 2;
            return !isset($this->figures[$xFrom][$yMiddle]);
        }
        return true;
    }
}
