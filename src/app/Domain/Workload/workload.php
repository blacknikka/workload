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
    private $count;
    private $date;

    /**
     * Workload constructor.
     * @param int|null $id
     * @param Project $project
     * @param Category $category
     * @param float $count
     * @param Carbon $date
     */
    public function __construct(
        ?int $id,
        Project $project,
        Category $category,
        float $count,
        Carbon $date
    ) {
        $this->id = $id;
        $this->project = $project;
        $this->category = $category;
        $this->count = $count;
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
    public function getCount(): float
    {
        return $this->count;
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
            'id' => $id,
            'project' => $this->project,
            'category' => $this->category,
            'count' => $this->count,
            'date' => $this->date,
        ];
    }
}
