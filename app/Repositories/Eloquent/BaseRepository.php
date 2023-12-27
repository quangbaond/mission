<?php
namespace App\Repositories\Eloquent;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * Specify Model class name
     *
     * @return string
     */
    abstract public function model(): string;

    /**
     * BaseRepository constructor.
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * @param int $limit
     * @param array $requester
     * @param array $columnCanSearchKeyword
     * @return array|Collection|LengthAwarePaginator
     * @throws Exception
     */
    public function pagination(int $limit = PAGE_SIZE, array $requester = [], array $columnCanSearchKeyword = []): array|Collection|LengthAwarePaginator
    {
        return isset($requester['all']) && $requester['all']
            ? $this->search($requester, $columnCanSearchKeyword)->get()
            : $this->search($requester, $columnCanSearchKeyword)->paginate($limit);
    }

    /**
     * @param array $requester
     * @param array $columnCanSearchKeyword
     * @return Builder
     * @throws Exception
     */
    public function search(array $requester = [], array $columnCanSearchKeyword = []): Builder
    {
        $query = $this->model->query();
        collect($requester)->each(
            callback: fn($value, $key) => match ($key) {
            'keyword' => $query->when(
                value: $value,
                callback: fn($query)
                => $query->where(
                    fn($query) =>
                    collect($columnCanSearchKeyword)->each(
                        callback: fn($column) => $this->queryLike(column: $column, value: $value, query: $query)
                    ),
                )
            ),
            'select' => $this->querySelect(query: $query, columns: $value),
            'orderByColumn' => collect($value)->each(callback: fn($column, $k) => $this->queryOrderByColumn(column: $column, direction: $requester['orderBy'], query: $query)),
            'orderBy', 'page', 'limit', 'all' => $query,
            'with' => $value ? $query->with($value) : $query,
            default => !is_null($value) ? $this->queryDefault(column: $key, value: $value, query: $query) : $query,
        });
        return $query;
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function makeModel(): Model
    {
        $model = app()->make($this->model());
        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        return $this->model = $model;
    }

    /**
     * @param string $column
     * @param string $value
     * @param Builder $query
     * @return Builder
     * @throws Exception
     */
    public function queryDefault(string $column, string $value, Builder $query): Builder
    {
        // if not exit $column throw error
        if (!in_array($column, $this->model->getFillable())) {
           return !env('APP_DEBUG')
               ? $query
               : throw new Exception(message: 'Column ' . $column . ' not exist in table ' . $this->model->getTable(), code: 500);
        }

        return $query->when(!is_null($value), function ($q) use ($column, $value) {
            return $q->where($column, $value);
        }, function ($q) use ($column) {
            return $q->whereNull($column);
        });

    }

    /**
     * @param string $column
     * @param string $direction
     * @param Builder $query
     * @return Builder
     */
    public function queryOrderByColumn(string $column, string $direction, Builder $query): Builder
    {
        $typeDirection = $direction === 'asc' ? 'asc' : 'desc';
        return $query->orderBy(column: $column, direction: $typeDirection);
    }

    /**
     * @param string $column
     * @param string $value
     * @param Builder $query
     * @return Builder
     */
    public function queryLike(string $column, string $value, Builder $query): Builder
    {
        return $query->orWhere($column, 'like', "%$value%");
    }

    /**
     * @param int $id
     * @param array $columns
     * @param array|string|null $with
     * @param array|string|null $withCount
     * @return Model|Collection|Builder|array|null
     */
    public function find(int $id, array $columns = ['*'], array|string|null $with = null, array|string|null $withCount = null): Model|\Illuminate\Database\Eloquent\Collection|Builder|array|null
    {
        $query = $this->model->query();

        if ($with) {
            $query->with($with);
        }

        if ($withCount) {
            $query->withCount($withCount);
        }
        return $query->findOrFail($id, $columns);
    }

    /**
     * @param Builder $query
     * @param array|string $columns
     * @return Builder
     * @throws Exception
     */
    public function querySelect(Builder $query, array|string $columns = ['*']): Builder
    {
        if($columns === ['*']) return $query->addSelect($columns);
        if(is_string($columns)) $columns = explode(',', $columns);
        // if not exit $column throw error
        collect($columns)->each(
            callback: fn($column)
            => (in_array($column, $this->model->getFillable())
                ? $query->addSelect($column) : !env('APP_DEBUG')) ? $query
                : throw new Exception(message: 'Column ' . $column . ' not exist in table ' . $this->model->getTable(), code: 500)
        );
        return $query;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->query()->create($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function update(array $data, int $id): bool
    {
        return $this->find($id)->update($data);
    }

    /**
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function updateOrCreate(array $data): Model
    {
        return $this->model->query()->updateOrCreate($data);
    }

    /**
     * @param int $id
     * @return Model
     *
     * @throws Exception
     */
    public function delete(int $id): Model
    {
        $model = $this->find($id);
        return $model->delete();
    }

    public function uploadFileStorage(string $path, string $file, string $disk = 'public'): string
    {
        return Storage::disk($disk)->putFile($path, $file);
    }

    /**
     * @throws Exception
     */
    public function withCount($requester = [], $columnCanSearchKeyword = ['*']): int
    {
        return $this->search(requester: $requester, columnCanSearchKeyword: $columnCanSearchKeyword)->count();
    }
}
