<?php
declare(strict_types=1);

namespace App\Domain\Workload;

/**
 * Class Project
 * @package App\Domain\Workload
*/
class Project
{
    private $id;
    private $name;
    private $comment;

    /**
     * Project constructor.
     * @param int|null $id
     * @param string $name
     * @param string $comment
     */
    public function __construct(
        ?int $id,
        string $name,
        string $comment
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->comment = $comment;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'comment' => $this->comment,
        ];
    }
}
