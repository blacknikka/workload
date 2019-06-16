<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Infrastructure\Db\DepartmentDao;

/**
 * Class DepartmentRepository
 * @package App\Domain\User
 */
class DepartmentRepository
{
    /** @var DepartmentDao */
    private $departmentDao;

    /**
     * DepartmentRepository constructor.
     * @param DepartmentDao $departmentDao
     */
    public function __construct(DepartmentDao $departmentDao)
    {
        $this->departmentDao = $departmentDao;
    }

    /**
     * @param Department $department
     * @return int
     */
    public function save(Department $department) : int
    {
        return $this->departmentDao->save($department);
    }

    /**
     * @param int $id
     * @return Department|null
     */
    public function findById(int $id) : ?Department
    {
        return $this->departmentDao->find($id);
    }

    /**
     * if exists.
     *
     * @param string $name
     * @return boolean
     */
    public function exists(string $name) : bool
    {
        return $this->departmentDao->exists($name);
    }

    /**
     * if exists (by ID).
     *
     * @param integer $id
     * @return boolean
     */
    public function existsById(int $id) : bool
    {
        return $this->departmentDao->existsById($id);
    }
}
