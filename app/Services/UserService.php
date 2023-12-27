<?php

namespace App\Services;

use App\Services\BaseService;
use App\Repositories\Eloquent\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService extends BaseService
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * $userRepository constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct($userRepository);
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $requester
     * @return array|Collection|LengthAwarePaginator
     * @throws Exception
     */
    public function search(array $requester = []): array|Collection|LengthAwarePaginator
    {
        $columnCanSearchKeyword = ['name', 'email', 'username', 'address', 'number_phone', 'created_at', 'updated_at'];
        $limit = $requester['limit'] ?? PAGINATE_DEFAULT;
        return $this->userRepository->pagination(limit: $limit, requester: $requester, columnCanSearchKeyword: $columnCanSearchKeyword);
    }

    /**
     * @throws Exception
     */
    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->userRepository->create($data);
    }

    public function userMission(array $requester = [], bool $count = true): Collection|int|array
    {
        return $this->userRepository->userMission($requester, $count);
    }

    public function doTask(array $requester = [])
    {
        return $this->userRepository->doTask($requester);
    }

    /**
     * @param array $requester
     * @param $user
     * @return mixed
     */
    public function withDraw(array $requester, $user): mixed
    {
        return $this->userRepository->withDraw($requester, $user);
    }

}
