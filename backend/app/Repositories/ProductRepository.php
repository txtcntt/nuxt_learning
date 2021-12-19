<?php

namespace App\Repositories;

use App\Models\MstProduct;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductRepository
 *
 * @package App\Repositories
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * @var MstProduct
     */
    protected $model;

    /**
     * ProductRepository constructor.
     *
     * @param MstProduct $model
     */
    public function __construct(MstProduct $model)
    {
        parent::__construct($model);
    }

    /** 
     * Get products information with condition and paging
     * @param $params = [
     *  'name' => product name,
     *  'status' => (1|0),
     *  'fprice' => price from,
     *  'tprice' => price to,
     *  'page' => current page number
     * ]
     * @return Pagination
     */
    public function searchProduct($params = [])
    {
        $fields = ['product_id', 'product_name', 'description', 'product_price', 'is_sales', 'product_image'];
        $currentPage = 1;
        $conditions = [];
        if (!empty($params)) {
            if (isset($params['name']) && $params['name'] != '') {
                $conditions[] = ['product_name', 'like', '%' . $params['name'] . '%'];
            }
            if (isset($params['fprice']) && $params['fprice'] != '') {
                $conditions[] = ['product_price', '>=', $params['fprice']];
            }
            if (isset($params['tprice']) && $params['tprice'] != '') {
                $conditions[] = ['product_price', '<=', $params['tprice']];
            }
            if (isset($params['status']) && in_array($params['status'], [0, 1, 2])) {
                $conditions[] = ['is_sales', '=', $params['status']];
            }
            if (isset($params['page']) && $params['page'] != '') {
                $currentPage = $params['page'];
            }
        }
        if (empty($conditions)) {
            return $this->model->orderBy('product_id', 'desc')->orderBy('created_at', 'desc')
                ->paginate(config('constants.item_per_page'), $fields, 'page', $params['page']);
        }
        return $this->model->where($conditions)->orderBy('created_at', 'desc')->orderBy('product_id', 'desc')
            ->paginate(config('constants.item_per_page'), $fields, 'page', $currentPage);
    }

    /** 
     * Insert new product information
     * 
     * @param $params = [
     *  'product_name' => ,
     *  'product_image' => ,
     *  'product_price' => ,
     *  'description' => ,
     *  'is_sales' => ,
     *  'image' => //file upload
     * ]
     * @return boolean
     */
    public function createProduct($params)
    {
        if (!in_array($params['is_sales'], [0, 1, 2])) {
            $params['is_sales'] = 1;
        }
        if ($params['product_price'] == '') {
            $params['product_price'] = 0;
        }
        $params['created_at'] = now();        
        $params['product_id'] = $this->generatorProductId($params['product_name'][0]);
        if ($params['image'] != '') {
            $imageName = $this->saveProductImage($params['image'], $params['product_image'], $params['product_id']);
            if (empty($imageName)) {
                return false;
            }
        }
        //clear image file input
        unset($params['image']);
        return $this->create($params);
    }

    /** 
     * Update product information
     * 
     * @param $params = [
     *  'product_name' => ,
     *  'product_image' => ,
     *  'product_price' => ,
     *  'description' => ,
     *  'is_sales' => ,
     *  'image' => //file upload
     * ]
     * @return boolean
     */
    public function updateProduct($id, $params)
    {
        $productInfo = $this->findById($id);
        if (empty($productInfo)) {
            return false;
        }

        //if has upload new image
        if (!empty($params['image'])) {
            $imageName = $this->saveProductImage($params['image'], $params['product_image'], $id);
            if (empty($imageName)) {
                return false;
            }
            $params['product_image'] = $imageName;
        }
        //if change image name
        else if (!empty($productInfo->product_image) && !empty($params['product_image']) && $params['product_image'] != $productInfo->product_image) {
            //replace current name to new name            
            $reNameResult = $this->renameProductImage($productInfo->product_image, $params['product_image'], $id);
            if ($reNameResult == 0) {
                return false;
            } elseif ($reNameResult == 2) {
                $params['product_image'] = '';
            }
        }

        if (!in_array($params['is_sales'], [0, 1, 2])) {
            $params['is_sales'] = 1;
        }
        $params['updated_at'] = now();
        //clear image file input
        unset($params['image']);
        return $this->update($id, $params);
    }

    /**
     * Generate new product id
     * 
     * @return string
     * 
     */
    private function generatorProductId($prefix)
    {
        $prefix = strtoupper($prefix);
        $lastProductId = $this->model->where('product_id', 'like', $prefix . '%')->max('product_id');
        $idNumber = 1;
        if (!empty($lastProductId)) {
            $idNumber = intval(str_replace($prefix, '', $lastProductId));
            $idNumber++;
        }
        return $prefix . str_pad($idNumber, config('constants.product_id_length'), '0', STR_PAD_LEFT);
    }

    /**
     * Save product image to local disk
     * 
     * @param $file 
     * @param $fileName
     * @param $folderName
     * 
     * @return string (empty: save image error, string file name: save image success)
     */
    public function saveProductImage($file, $fileName, $folderName)
    {
        if (empty($file)) {
            return '';
        }
        try {
            if (empty($fileName)) {
                $fileName = $file->getClientOriginalName();
            }
            $path = '/public/' . config('constants.folder_path.product') . '/' . $folderName;
            if (!Storage::disk('image')->exists($path)) {
                Storage::disk('image')->makeDirectory($path);
            }
            Storage::disk('image')->putFileAs($path, $file, $fileName);
            // $file->storeAs(Storage::disk('image')->path($path),  $fileName);
            return $fileName;
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return '';
        }
    }

    /**
     * Rename product image
     * 
     * @param $oldName
     * @param $newName
     * @param $folderName
     * 
     * @return int (0: error, 1: success, 2: file is deleted)
     */
    public function renameProductImage($oldName, $newName, $productId)
    {
        try {
            $path =  'public/' . config('constants.folder_path.product') . '/' . $productId . '/';
            if (Storage::exists($path . $oldName)) {
                Storage::move($path . $oldName, $path . $newName);
                return 1;
            }
            return 2;
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return 0;
        }
    }

    /**
     * Delete product image
     * 
     * @param $productId
     * @return boolean
     */
    public function deleteProductImage($productId)
    {
        try {
            $productInfo = $this->findById($productId);
            //product is deleted
            if (empty($productInfo)) {
                return false;
            }

            if (!empty($productInfo->product_image)) {
                $path =  '/public/' . config('constants.folder_path.product') . '/' . $productId . '/' . $productInfo->product_image;
                if (Storage::disk('image')->exists($path)) {
                    Storage::disk('image')->delete($path);
                }
                $productInfo->product_image = '';
                $productInfo->save();
            }
            return true;
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return false;
        }
    }
}
