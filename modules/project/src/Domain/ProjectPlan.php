<?php

namespace Modules\Project\Domain;

class ProjectPlan
{
    private string $id;
    private string $name;
    private array $milestones = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addMilestone(Milestone $milestone)
    {
        $this->milestones[] = $milestone;
    }

    public function getMilestones(): array
    {
        return $this->milestones;
    }
}
