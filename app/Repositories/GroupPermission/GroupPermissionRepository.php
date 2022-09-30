<?php

namespace App\Repositories\GroupPermission;

use App\Models\GroupPermission;
use App\Repositories\Base\BaseRepository;
use JetBrains\PhpStorm\Pure;

class GroupPermissionRepository extends BaseRepository implements GroupPermissionRepositoryInterface
{
    #[Pure] public function __construct(GroupPermission $model)
    {
        parent::__construct($model);
    }
}