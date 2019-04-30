<?php
declare(strict_types=1);

namespace App\Domain\Workload;

use Carbon\Carbon;

/**
 * Class Workload
 * @package App\Domain\Workload
 */
class Workload
{
    private $id;
    private $project;
    private $category;
    private $amount;
    private $date;

    /**
     * Workload constructor.
     * @param int|null $id
     * @param Project $project
     * @param Category $category
     * @param float $amount
     * @param Carbon $date
     */
    public function __construct(
        ?int $id,
        Project $project,
        Category $category,
        float $amount,
        Carbon $date
    ) {
        $this->id = $id;
        $this->project = $project;
        $this->category = $category;
        $this->amount = $amount;
        $this->date = $date;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'project' => $this->project,
            'category' => $this->category,
            'amount' => $this->amount,
            'date' => $this->date,
        ];
    }
}
