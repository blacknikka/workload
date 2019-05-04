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
    private $user_id;
    private $project_id;
    private $category_id;
    private $amount;
    private $date;

    /**
     * Workload constructor.
     * @param int|null $id
     * @param int $user_id
     * @param int $project_id
     * @param int $category_id
     * @param float $amount
     * @param Carbon $date
     */
    public function __construct(
        ?int $id,
        int $user_id,
        int $project_id,
        int $category_id,
        float $amount,
        Carbon $date
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->project_id = $project_id;
        $this->category_id = $category_id;
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
     * User ID
     *
     * @return integer
     */
    public function getUserId() : int
    {
        return $this->user_id;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->project_id;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
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
            'project_id' => $this->project_id,
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'date' => $this->date,
        ];
    }
}

