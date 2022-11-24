<?php

namespace App\Repositories\Social;

use App\Models\Social;
use App\Repositories\Base\BaseRepository;

class SocialRepository extends BaseRepository implements SocialRepositoryInterface
{
    public function __construct(Social $model)
    {
        parent::__construct($model);
    }
}