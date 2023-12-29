<?php

namespace App\Repositories\Eloquent;
use App\Models\UserMission;
use Exception;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function update(array $data, int $id): bool
    {
        if(isset($data['avatar'])) {
            if(!is_file($data['avatar'])) throw new Exception('File not found');
            $data['avatar'] = $this->uploadFileStorage(path: 'avatars', file: $data['avatar']);
        }
        return parent::update($data, $id);
    }

    /**
     * @throws Exception
     */
    public function create(array $data): Model
    {
        if(isset($data['avatar'])) {
            if(!is_file($data['avatar'])) throw new Exception('File not found');
            $data['avatar'] = $this->uploadFileStorage(path: 'avatars', file: $data['avatar']);
        }
        return parent::create($data);
    }

    public function userMission(array $requester = [], bool $count = true): \Illuminate\Database\Eloquent\Collection|int|array
    {
        $query = $this->model->query();
        $query->whereHas('mission', function ($query) use ($requester) {
            if(isset($requester['mission_id'])) {
                $query->where('mission_id', $requester['mission_id']);
            }
        });
        if($count) return $query->count();
        return $query->get();
    }

    /**
     * @param array $requester
     * @return mixed
     */
    public function doTask(array $requester = [])
    {
        // thêm 1 record vào bảng user_mission
        $user = $this->model->query()->find($requester['user_id']);
        $user->mission()->attach($requester['mission_id']);
        // cập nhật lại số dư của user
//        $user->balance = $user->balance + $requester['reward'];
//        $user->balance_deposit = $user->balance_deposit + $requester['reward'];
//        $user->save();
        return $user;
    }

    /**
     * @param array $requester
     * @param $user
     * @return mixed
     */
    public function withDraw(array $requester, $user): mixed
    {
        $user->balance = $user->balance - $requester['amount'];
        $user->balance_pending = $user->balance_pending + $requester['amount'];
        $user->save();
        return $user;
    }

}
