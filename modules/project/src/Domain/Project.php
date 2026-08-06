<?php

namespace Modules\Project\Domain;

use Modules\Project\Constants\ProjectStatus;
use Modules\Project\Constants\ProjectType;

class Project
{
    private string $id;
    private string $name;
    private string $description;
    private Client $client;
    private ProjectPlan $plan;
    private string $status; // Initiated, In Progress, etc.
    private string $type;   // Local, Foreign
    private \DateTime $deliveryDate;
    private \DateTime $deadline;
    private array $costs = [];
    private array $incomes = [];

    public function __construct(string $name, Client $client)
    {
        $this->name = $name;
        $this->client = $client;
        $this->status = ProjectStatus::INITIATED;
    }

    public function setProjectPlan(ProjectPlan $plan)
    {
        $this->plan = $plan;
    }

    public function addDetails(string $description, string $type = ProjectType::LOCAL)
    {
        $this->description = $description;
        $this->type = $type;
    }

    public function setScheduledDates(\DateTime $deliveryDate, \DateTime $deadline)
    {
        $this->deliveryDate = $deliveryDate;
        $this->deadline = $deadline;
    }

    public function updateStatus(string $status)
    {
        if (in_array($status, ProjectStatus::all())) {
            $this->status = $status;
        }
    }

    public function addCostFactor(string $factor, float $amount, string $type = 'Direct')
    {
        $this->costs[] = new ProjectCost($factor, $amount, $type);
    }

    public function addEmployeeCost(string $employeeName, float $hourlyRate, float $hours)
    {
        $this->costs[] = ProjectCost::fromEmployee($employeeName, $hourlyRate, $hours);
    }

    public function addIncome(string $source, float $amount)
    {
        $this->incomes[] = new ProjectIncome($source, $amount);
    }

    public function calculateTotalCost(): float
    {
        return array_reduce($this->costs, fn($total, $cost) => $total + $cost->getAmount(), 0.0);
    }

    public function calculateProfit(): float
    {
        $totalIncome = array_reduce($this->incomes, fn($total, $income) => $total + $income->getAmount(), 0.0);
        return $totalIncome - $this->calculateTotalCost();
    }
}
