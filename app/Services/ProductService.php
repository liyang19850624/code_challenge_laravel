<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\TagRepository;
use Exception;

class ProductService
{
    private $productRepository;
    private $tagRepository;

    public function __construct(ProductRepository $productRepository, TagRepository $tagRepository)
    {
        $this->productRepository = $productRepository;
        $this->tagRepository = $tagRepository;
    }

    public function list()
    {
        return $this->productRepository->list();
    }

    public function get(int $id)
    {
        return $this->productRepository->get($id);
    }

    public function create(string $name, ?string $description, ?string $image_url)
    {
        return $this->productRepository->create(
            array_filter(
                [
                    'name' => $name,
                    'description' => $description,
                    'image_url' => $image_url
                ]
            )
        );
    }

    public function update(int $id, string $name, ?string $description, ?string $image_url)
    {
        $product = $this->productRepository->get($id);
        if (!$product) {
            throw new Exception("Cannot find product", 404);
        }
        $this->productRepository->update(
            $product,
            array_filter(
                [
                    'name' => $name,
                    'description' => $description,
                    'image_url' => $image_url
                ]
            )
        );
    }

    public function delete(int $id)
    {
        $product = $this->productRepository->get($id);
        if ($product) {
            $this->productRepository->delete($product);
        }
    }

    public function updateTag(int $id, ?string $tags) {
        $product = $this->get($id);
        if (!$product) {
            throw new Exception("Cannot find resource", 404);
        }
        $tagList = array_map('trim', str_getcsv($tags));
        
        $this->tagRepository->createByNames(
            array_diff(
                $tagList, 
                $this->tagRepository->getByNames($tagList)->pluck('tag_name')->toArray(),
            )
        );
        $existingTagIds = $product->tags()->pluck('tags.id')->toArray();
        $newTagIds = $this->tagRepository->getByNames($tagList)->pluck('tags.id')->toArray();

        $tagIdToAttach = array_diff($newTagIds, $existingTagIds);
        $this->productRepository->attachTags($product, $tagIdToAttach);

        $tagIdToDetach = array_diff($existingTagIds, $newTagIds);
        $this->productRepository->detachTags($product, $tagIdToDetach);
    }
}
