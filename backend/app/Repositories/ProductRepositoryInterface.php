<?php

namespace App\Repositories;

/**
 * Interface ProductRepositoryInterface
 *
 * @package App\Repositories
 */
interface ProductRepositoryInterface extends RepositoryInterface
{    
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
    public function searchProduct($params = []);
    
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
    public function createProduct($params);
    
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
    public function updateProduct($id, $params);
    
    
    /**
     * Save product image to local disk
     * 
     * @param $file 
     * @param $fileName
     * @param $folderName
     * 
     * @return string (empty: save image error, string file name: save image success)
     */
    public function saveProductImage($file, $fileName, $folderName);

    /**
     * Rename product image
     * 
     * @param $oldName
     * @param $newName
     * @param $folderName
     * @return boolean
     */
    public function renameProductImage($oldName, $newName, $productId);

    /**
     * Delete product image
     * 
     * @param $productId
     * @return boolean
     */
    public function deleteProductImage($productId);
}