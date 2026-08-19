<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\Category;

/**
 * Admin Category Controller
 *
 * CRUD operations for product categories (owner only).
 */
class CategoryController extends AdminController
{
    private Category $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    /**
     * List all categories with optional edit mode.
     */
    public function index(Request $request): void
    {
        $this->requireOwner();

        $categories = $this->categoryModel->all('id DESC');

        // Check if editing
        $edit = null;
        if ($request->query('edit')) {
            $edit = $this->categoryModel->find((int)$request->query('edit'));
        }

        $this->adminView('admin.categories', [
            'current_page' => 'categories',
            'categories' => $categories,
            'edit' => $edit,
        ]);
    }

    /**
     * Store or update a category.
     */
    public function store(Request $request): void
    {
        $this->requireOwner();

        $id = (int)$request->input('id', 0);

        $data = [
            'name' => trim($request->input('name', '')),
            'slug' => trim($request->input('slug', '')),
            'icon' => trim($request->input('icon', '')),
            'status' => $request->input('status') ? 1 : 0,
        ];

        $this->categoryModel->save($data, $id ?: null);
        $this->redirect('/admin/categories');
    }

    /**
     * Delete a category.
     */
    public function delete(Request $request): void
    {
        $this->requireOwner();

        $id = (int)$request->query('id', 0);

        if ($id) {
            $this->categoryModel->delete($id);
        }

        $this->redirect('/admin/categories');
    }
}
