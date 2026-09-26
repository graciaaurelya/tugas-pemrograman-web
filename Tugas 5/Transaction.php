<?php

declare(strict_types=1);

class Transaction
{
    public function __construct(
        private string $id,
        private string $type,
        private float $amount
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function process(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['balance'] ??= 0.0;

        return false;
    }

    private function processDeposit(): bool
    {
        $_SESSION['balance'] += $this->amount;
        return true;
    }

    private function processWithdrawal(): bool
    {
        if ($_SESSION['balance'] < $this->amount) {
            return false;
        }

        $_SESSION['balance'] -= $this->amount;
        return true;
    }
}
