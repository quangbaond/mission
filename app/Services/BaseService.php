<?php
namespace App\Services;

use App\Repositories\Eloquent\BaseRepository;
use Exception as ExceptionAlias;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    /**
     * @var BaseRepository
     */
    public BaseRepository $repository;
    /**
     * BaseService constructor.
     *
     * @param BaseRepository $repository
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @param $id
     * @return Model
     */
    public function find(int $id) :Model
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool
     * @throws ExceptionAlias
     */
    public function update(array $data, int $id) : bool
    {
        return $this->repository->update($data, $id);
    }
    /**
     * @param int $id
     *
     * @return Model
     * @throws ExceptionAlias
     */
    public function delete(int $id) : Model
    {
        return $this->repository->delete($id);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data) : Model
    {
        return $this->repository->create($data);
    }
}

