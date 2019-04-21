<?php
declare(strict_types=1);

namespace App\Domain\User;

/**
 * Class User
 * @package App\Domain\User
 */
class Department
{
    public $id;
    private $name;
    private $sectionName;
    private $comment;

    /**
     * Department constructor.
     * @param int|null $id
     * @param string $name
     * @param string $sectionName
     * @param string $comment
     */
    public function __construct(
        ?int $id,
        string $name,
        string $sectionName,
        string $comment
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->sectionName = $sectionName;
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
    public function getSectionName(): string
    {
        return $this->sectionName;
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
            'id' => $id,
            'name' => $this->name,
            'sectionName' => $this->sectionName,
            'comment' => $this->name,
        ];
    }
}
