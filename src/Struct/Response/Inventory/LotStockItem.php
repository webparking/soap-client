<?php

declare(strict_types=1);

namespace DistriMedia\SoapClient\Struct\Response\Inventory;

class LotStockItem
{
    const PIECES = 'Pieces';
    const PROBLEM = 'Problem';
    const OVERDUE = 'Overdue';
    const BLOCKED = 'Blocked';
    const LOT_NUMBER = 'LotNumber';
    const DUE_DATE = 'DueDate';
    const LAST_PICKABLE_DATE = 'LastPickableDate';

    private $pieces;
    private $problem;
    private $overdue;
    private $blocked;
    private $lotNumber;
    private $dueDate;
    private $lastPickableDate;

    public function __construct(
        array $data = []
    )
    {
        $this->pieces = isset($data[self::PIECES]) ? $data[self::PIECES] : null;
        $this->problem = isset($data[self::PROBLEM]) ? $data[self::PROBLEM] : null;
        $this->overdue = isset($data[self::OVERDUE]) ? $data[self::OVERDUE] : null;
        $this->blocked = isset($data[self::BLOCKED]) ? $data[self::BLOCKED] : null;
        $this->lotNumber = isset($data[self::LOT_NUMBER]) ? $data[self::LOT_NUMBER] : null;
        $this->dueDate = isset($data[self::DUE_DATE]) ? $data[self::DUE_DATE] : null;
        $this->lastPickableDate = isset($data[self::LAST_PICKABLE_DATE]) ? $data[self::LAST_PICKABLE_DATE] : null;
    }

    public function getLastPickableDate(): mixed
    {
        return $this->lastPickableDate;
    }

    public function getDueDate(): mixed
    {
        return $this->dueDate;
    }

    public function getLotNumber(): mixed
    {
        return $this->lotNumber;
    }

    public function getBlocked(): mixed
    {
        return $this->blocked;
    }

    public function getOverdue(): mixed
    {
        return $this->overdue;
    }

    public function getProblem(): mixed
    {
        return $this->problem;
    }

    public function getPieces(): mixed
    {
        return $this->pieces;
    }
}
