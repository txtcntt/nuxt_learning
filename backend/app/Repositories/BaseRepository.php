<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 *
 * @package App\Repositories
 */
class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all
     * 
     * @return collection
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get by id
     * 
     * @param @id
     * 
     * @return model
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function update($id, $attributes)
    {
        $model = $this->findById($id);
        if ($model) {
            return $model->update($attributes);
        }
        return false;
    }

    public function delete($id)
    {
        $model = $this->findById($id);
        if ($model) {
            return $model->delete();
        }
        return false;
    }

    public function create($attributes)
    {
        $result = $this->model->create($attributes);
        if ($result) {
            return true;
        }
        return false;
    }
}
