<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\Category;
use App\Models\Product;
use App\Services\FileUploadService;

/**
 * Admin Product Controller
 *
 * CRUD operations for products (owner only).
 */
class ProductController extends AdminController
{
    private Product $productModel;
    private Category $categoryModel;
    private FileUploadService $uploadService;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->uploadService = new FileUploadService();
    }

    /**
     * List all products with optional filters.
     */
    public function index(Request $request): void
    {
        $this->requireOwner();

        // Parse filters
        $statusFilter = $request->query('status');
        $categoryFilter = $request->query('cat');

        $isActive = match ($statusFilter) {
            'active' => true,
            'inactive' => false,
            default => null,
        };

        $categoryId = $categoryFilter ? (int)$categoryFilter : null;

        $products = $this->productModel->getAllWithCategory($categoryId, $isActive);
        $categories = $this->categoryModel->getActive();

        // Check if editing
        $edit = null;
        if ($request->query('edit')) {
            $edit = $this->productModel->find((int)$request->query('edit'));
        }

        $this->adminView('admin.products', [
            'current_page' => 'products',
            'products' => $products,
            'categories' => $categories,
            'edit' => $edit,
            'filters' => [
                'status' => $statusFilter,
                'cat' => $categoryFilter,
            ],
        ]);
    }

    /**
     * Store or update a product.
     */
    public function store(Request $request): void
    {
        $this->requireOwner();

        $id = (int)$request->input('id', 0);
        $image = $request->input('old_image');

        // Handle file upload
        if ($request->hasFile('image')) {
            $uploadResult = $this->uploadService->uploadProductImage($request->file('image'));

            if (!$uploadResult['success']) {
                echo $uploadResult['error'];
                return;
            }

            $image = $uploadResult['filename'];
        }

        $data = [
            'name' => trim($request->input('name', '')),
            'category_id' => (int)$request->input('category_id', 0),
            'price' => (int)$request->input('price', 0),
            'stock' => (int)$request->input('stock', 0),
            'image' => $image,
            'is_active' => $request->input('is_active') ? 1 : 0,
        ];

        $this->productModel->save($data, $id ?: null);
        $this->redirect('/admin/products');
    }
}
