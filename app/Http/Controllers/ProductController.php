<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ApiResponse;
    
    public function __construct(private ProductService $productService) {}

    public function index(Request $request): JsonResponse
    {
        $validatedFilters = $request->validate([
            'category' => 'sometimes|string|nullable',
            'priceLessThan' => 'sometimes|integer|min:1|nullable'
        ]);

        try {
            $products = $this->productService->getDiscountedProducts($validatedFilters);

            return $this->successResponse($products['data'], 'Products retrieved successfully', $products['meta']);
        } catch (Exception $e) {
            return $this->errorResponse(
                'An unexpected error occurred. Please try again later.'
            );
        }
    }
}
