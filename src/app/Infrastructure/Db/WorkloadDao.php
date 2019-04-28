<?php
declare(strict_types=1);

namespace App\Infrastructure\Db;

use App\Domain\User\Workload;
use App\Domain\User\Project;
use App\Domain\User\Category;
use Carbon\Carbon;
use DB;

/**
 * Class WorkloadDao
 * @package App\Infrastructure\Db
 */
class WorkloadDao
{
    const USER_TABLE_NAME = 'user';
    const DEP_TABLE_NAME = 'department';
}
