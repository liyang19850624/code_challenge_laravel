<?php
namespace App\Repositories;

use App\Models\Product;

class ProductRepository {
    public function list() {
        return Product::all();
    }

    public function get(int $id) {
        return Product::find($id);
    }

    public function create(array $fields) {
        return Product::create($fields);
    }

    public function update(Product $product, array $updatedFields) {
        $product->update($updatedFields);
    }

    public function delete(Product $product) {
        $product->delete();
    }

    public function attachTags(Product $product, array $tagIds) {
        foreach($tagIds as $tagId) {
            $product->tags()->attach($tagId);
        }
    }

    public function detachTags(Product $product, array $tagIds) {
        foreach($tagIds as $tagId) {
            $product->tags()->detach($tagId);
        }
    }
}