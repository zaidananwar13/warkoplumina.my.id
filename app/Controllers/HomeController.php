<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Category;

/**
 * Home Controller
 *
 * Handles the public homepage displaying category navigation.
 */
class HomeController extends Controller
{
    private Category $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    /**
     * Display the homepage with active categories.
     */
    public function index(Request $request): void
    {
        $categories = $this->categoryModel->getActive();

        $this->view('home.index', [
            'categories' => $categories,
        ]);
    }
}
