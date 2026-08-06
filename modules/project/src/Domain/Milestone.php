<?php

namespace Modules\Project\Domain;

class Milestone
{
    private string $id;
    private string $description;
    private \DateTime $scheduledDate;
    private int $actualDuration; // in days or hours
    private bool $isCompleted = false;

    public function __construct(string $description, \DateTime $scheduledDate)
    {
        $this->description = $description;
        $this->scheduledDate = $scheduledDate;
    }

    public function markAsCompleted(int $actualDuration)
    {
        $this->isCompleted = true;
        $this->actualDuration = $actualDuration;
    }

    public function getScheduledDate(): \DateTime
    {
        return $this->scheduledDate;
    }

    public function getActualDuration(): int
    {
        return $this->actualDuration;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }
}
