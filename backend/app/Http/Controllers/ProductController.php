<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductInfoRequest;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * ProductController constructor.
     * 
     * @param ProductRepositoryInterface $userRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        return view(
            'product',
            [
                'status' => json_encode(config('constants.product_status')),
                'perpage' => config('constants.item_per_page'),
                'conditions' => $this->getSearchParams($request),
                'image_path' => 'storage/' . config('constants.folder_path.product') . '/',
            ]
        );
    }

    /**
     * Search product list with conditions
     * 
     * @return json_encode
     */
    public function search(Request $request)
    {
        $products = $this->productRepository->searchProduct($this->getSearchParams($request));
        return $this->sendResponse($products, 'product list');
    }

    /**
     * Delete product info
     * 
     * @param $request (required input name id)
     * @return json_encode
     */
    public function deleteProduct(Request $request)
    {
        $result = $this->productRepository->delete($request->input('id'));
        if ($result) {
            return $this->sendResponse([], trans('message.products.action_success', ['action' => 'Delete']));
        }
        return $this->sendError(trans('message.products.action_failed', ['action' => 'Delete']));
    }

    /**
     * Insert new product information
     */
    public function storeProduct(ProductInfoRequest $request)
    {
        $params = $this->getProductParams($request);

        if ($request->hasFile('image')) {
            $params['image'] = $request->file('image');
        }

        $result = $this->productRepository->createProduct($params);
        if ($result) {
            return $this->sendResponse([], trans('message.products.action_success', ['action' => 'Thêm mới']));
        }
        return $this->sendError(trans('message.products.action_failed', ['action' => 'Thêm mới']));
    }

    /**
     * Update product information
     */
    public function saveProduct(ProductInfoRequest $request)
    {
        $params = $this->getProductParams($request);

        if ($request->has('image')) {
            $params['image'] = $request->file('image');
        }
        $id = $request->input('id');

        $result = $this->productRepository->updateProduct($id, $params);
        if ($result) {
            return $this->sendResponse([], trans('message.products.action_success', ['action' => 'Cập nhật']));
        }
        return $this->sendError(trans('message.products.action_failed', ['action' => 'Cập nhật']));
    }

    public function deleteProductImage(Request $request)
    {
        $id = $request->input('id', '');
        if (!empty($id)) {
            $deleteResult = $this->productRepository->deleteProductImage($id);
            if ($deleteResult) {
                return $this->sendResponse([], trans('message.products.action_success', ['action' => 'Xóa hình sản phẩm']));
            }
        }
        return $this->sendError(trans('message.products.action_failed', ['action' => 'Xóa hình sản phẩm']));
    }

    /**
     * Get search conditions param
     * this is common function using when load page and paging
     * 
     * @param $request
     * @return array
     */
    private function getSearchParams($request)
    {
        return [
            'name' => $request->input('name', ''),
            'fprice' => $request->input('fprice', ''),
            'tprice' => $request->input('tprice', ''),
            'status' => $request->input('status', ''),
            'page' => $request->input('page', 1),
        ];
    }

    /**
     * Get product request params
     * using for insert or update product information
     * 
     * @param $request
     * @return Array
     */
    private function getProductParams($request)
    {
        return [
            'product_name' => $request->input('name'),
            'product_image' => $request->input('image_name', ''),
            'product_price' => $request->input('price', 0),
            'description' => $request->input('description', ''),
            'is_sales' => $request->input('status', ''),
            'image' => '',
        ];
    }
}
