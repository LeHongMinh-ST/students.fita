<?php

namespace App\Providers;

use App\Models\Menu;
use App\Repositories\Attachment\AttachmentRepository;
use App\Repositories\Attachment\AttachmentRepositoryInterface;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\Family\FamilyRepository;
use App\Repositories\Family\FamilyRepositoryInterface;
use App\Repositories\GeneralClass\GeneralClassRepository;
use App\Repositories\GeneralClass\GeneralClassRepositoryInterface;
use App\Repositories\GroupPermission\GroupPermissionRepository;
use App\Repositories\GroupPermission\GroupPermissionRepositoryInterface;
use App\Repositories\LearningOutcome\LearningOutcomeRepository;
use App\Repositories\LearningOutcome\LearningOutcomeRepositoryInterface;
use App\Repositories\Menu\MenuNodeRepository;
use App\Repositories\Menu\MenuNodeRepositoryInterface;
use App\Repositories\Menu\MenuRepository;
use App\Repositories\Menu\MenuRepositoryInterface;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Post\PostRepository;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\Reward\RewardRepository;
use App\Repositories\Reward\RewardRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\ScientificResearch\ScientificResearchRepository;
use App\Repositories\ScientificResearch\ScientificResearchRepositoryInterface;
use App\Repositories\Semester\SemesterRepository;
use App\Repositories\Semester\SemesterRepositoryInterface;
use App\Repositories\Social\SocialRepository;
use App\Repositories\Social\SocialRepositoryInterface;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Student\StudentRepositoryInterface;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Subject\SubjectRepositoryInterface;
use App\Repositories\Tag\TagRepository;
use App\Repositories\Tag\TagRepositoryInterface;
use App\Repositories\Teacher\TeacherRepository;
use App\Repositories\Teacher\TeacherRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SocialRepositoryInterface::class, SocialRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(GroupPermissionRepositoryInterface::class, GroupPermissionRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(FamilyRepositoryInterface::class, FamilyRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(GeneralClassRepositoryInterface::class, GeneralClassRepository::class);
        $this->app->bind(LearningOutcomeRepositoryInterface::class, LearningOutcomeRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
