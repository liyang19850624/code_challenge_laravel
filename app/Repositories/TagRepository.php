<?php
namespace App\Repositories;

use App\Models\Tag;

class TagRepository {
    public function get(int $id) {
        return Tag::find($id);
    }

    public function getByName(string $tagName) {
        return Tag::where('tag_name', $tagName)->first();
    }

    public function create(array $fields) {
        return Tag::create($fields);
    }
    
    public function getByNames(array $tagNames) {
        return Tag::whereIn('tag_name', $tagNames);
    }

    public function createByNames(array $tagNames) {
        $data = array_map(function($tagName) {
            return ['tag_name' => $tagName];
        }, $tagNames);
        if (!empty($data)) {
            Tag::insert($data);
        }
    }
}