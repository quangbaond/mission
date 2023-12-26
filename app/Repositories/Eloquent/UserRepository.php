<?php

namespace App\Repositories\Eloquent;
use Exception;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

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

}
