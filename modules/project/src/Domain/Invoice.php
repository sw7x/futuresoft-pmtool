<?php

namespace Modules\Project\Domain;

class Invoice
{
    private string $id;
    private string $invoiceNumber;
    private float $amount;
    private \DateTime $date;
    private string $status; // e.g., 'Draft', 'Sent', 'Paid', 'Cancelled'

    public function __construct(string $invoiceNumber, float $amount, \DateTime $date)
    {
        $this->invoiceNumber = $invoiceNumber;
        $this->amount = $amount;
        $this->date = $date;
        $this->status = 'Draft';
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function markAsPaid()
    {
        $this->status = 'Paid';
    }
}
