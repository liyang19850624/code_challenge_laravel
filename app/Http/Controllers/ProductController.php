<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route;

class ProductController extends Controller
{
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    public function index()
    {
        $req = Request::create('/api/products', 'GET');
        $response = Route::dispatch($req);
        $result = json_decode($response->getContent());
        if (!$result->result) {
            $existingProducts = [];
            $errorMessages = $result->result->errors;
        } else {
            $existingProducts = $result->data;
            $errorMessages = null;
        }

        return view(
            'products.view',
            [
                'products' => $existingProducts,
                'errorMessages' => $errorMessages
            ]
        );
    }

    public function create()
    {
        return view(
            'products.items.edit',
            [
                'product' => null
            ]
        );
    }

    public function edit(Request $request)
    {
        $req = Request::create('/api/product/' . $request->id, 'GET');
        $response = Route::dispatch($req);
        $result = json_decode($response->getContent());
        if (!$result->result) {
            $product = null;
            $errorMessages = $result->result->errors;
        } else {
            $product = $result->data;
            $errorMessages = null;
        }
        return view(
            'products.items.edit',
            [
                'product' => $product,
                'errorMessages' => $errorMessages
            ]
        );
    }
}
