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

        $success = match ($this->type) {
            'deposit' => $this->processDeposit(),
            'withdraw' => $this->processWithdraw(),
            default => false,
        };
 
        if ($success) {
            $_SESSION['transactions'][] = [
                'id' => $this->id,
                'type' => $this->type,
                'amount' => $this->amount,
            ];
        }
 
        return $success;

    }

    private function processDeposit(): bool
    {
        $_SESSION['balance'] += $this->amount;
        return true;
    }

    private function processWithdraw(): bool
    {
        if ($_SESSION['balance'] < $this->amount) {
            return false;
        }

        $_SESSION['balance'] -= $this->amount;
        return true;
    }
}
