<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Category;
use App\Models\Product;

/**
 * Category Controller
 *
 * Shows products within a specific category.
 */
class CategoryController extends Controller
{
    private Category $categoryModel;
    private Product $productModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->productModel = new Product();
    }

    /**
     * Display products for a given category slug.
     */
    public function show(Request $request, string $slug): void
    {
        $category = $this->categoryModel->findBySlug($slug);

        if (!$category || !$category['status']) {
            Response::notFound('Kategori tidak ditemukan');
            return;
        }

        $products = $this->productModel->getByCategoryId($category['id']);

        $this->view('category.show', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
