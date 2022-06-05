<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Models\Product;

class ProductTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'id'            => (int) $product->id,
            'name'          => (string) $product->name,
            'description'   => (string) $product->description,
            'image_url'       => (string) $product->image_url,
            'tags'           => implode(", ", $product->tags()->pluck('tag_name')->toArray())
        ];
    }
}
