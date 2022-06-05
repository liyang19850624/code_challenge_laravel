<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Product;
use App\Models\Tag;
use Tests\TestCase;

class ProductsApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_all()
    {
        $productOne = Product::factory()->make();
        $productOne->save();
        $productOneTagOne = Tag::factory()->make();
        $productOneTagOne->save();
        $productOneTagTwo = Tag::factory()->make();
        $productOneTagTwo->save();
        $productOne->tags()->attach($productOneTagOne);
        $productOne->tags()->attach($productOneTagTwo);

        $productTwo = Product::factory()->make();
        $productTwo->save();
        $productTwoTagOne = Tag::factory()->make();
        $productTwoTagOne->save();
        $productTwoTagTwo = Tag::factory()->make();
        $productTwoTagTwo->save();
        $productTwo->tags()->attach($productTwoTagOne);
        $productTwo->tags()->attach($productTwoTagTwo);

        $response = $this->get('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'image_url',
                        'tags'
                    ]
                ]
            ]);

        $responseContent = $response->decodeResponseJson();

        $this->assertEquals(1, $responseContent['result']);
        $responseData = $responseContent['data'];
        $this->assertEquals($productOne->name, $responseData[0]['name']);
        $this->assertEquals($productOne->description, $responseData[0]['description']);
        $this->assertEquals($productOne->image_url, $responseData[0]['image_url']);
        $this->assertEquals($productOneTagOne->tag_name . ", " . $productOneTagTwo->tag_name, $responseData[0]['tags']);
        $this->assertEquals($productTwo->name, $responseData[1]['name']);
        $this->assertEquals($productTwo->description, $responseData[1]['description']);
        $this->assertEquals($productTwo->image_url, $responseData[1]['image_url']);
        $this->assertEquals($productTwoTagOne->tag_name . ", " . $productTwoTagTwo->tag_name, $responseData[1]['tags']);
    }

    public function test_create_new()
    {
        $name = $this->faker->unique()->word();
        $description = $this->faker->text();
        $imageUrl = $this->faker->url();
        $tags = [
            $this->faker->word(),
            $this->faker->word(),
            $this->faker->word()
        ];

        $postData = [
            'name' => $name,
            'description' => $description,
            'image_url' => $imageUrl,
            'tags' => implode(",", $tags)
        ];
        $response = $this->postJson('/api/products', $postData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'image_url',
                    'tags'
                ]
            ]);

        $responseContent = $response->decodeResponseJson();

        $this->assertEquals(1, $responseContent['result']);
        $responseData = $responseContent['data'];
        $this->assertEquals($name, $responseData['name']);
        $this->assertEquals($description, $responseData['description']);
        $this->assertEquals($imageUrl, $responseData['image_url']);
        $this->assertEquals(implode(', ', $tags), $responseData['tags']);
        $this->assertDatabaseHas('products', [
            'name' => $name,
            'description' => $description,
            'image_url' => $imageUrl
        ]);
        $this->assertDatabaseHas('tags', [
            'tag_name' => $tags[0]
        ]);
        $this->assertDatabaseHas('tags', [
            'tag_name' => $tags[1]
        ]);
        $this->assertDatabaseHas('tags', [
            'tag_name' => $tags[2]
        ]);
        $product = Product::where('name', $name)->first();
        $this->assertCount(count($tags), $product->tags()->get());

        $this->assertEquals($tags, $product->tags()->pluck('tag_name')->toArray());
    }

    public function test_create_new_with_existing_tag()
    {
        $name = $this->faker->unique()->word();
        $description = $this->faker->text();
        $imageUrl = $this->faker->url();
        $tagOne = Tag::factory()->make();
        $tagOne->save();
        $tagTwo = Tag::factory()->make();
        $tagTwo->save();


        $postData = [
            'name' => $name,
            'description' => $description,
            'image_url' => $imageUrl,
            'tags' => $tagOne->tag_name . "," . $tagTwo->tag_name
        ];
        $response = $this->postJson('/api/products', $postData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'image_url',
                    'tags'
                ]
            ]);

        $responseContent = $response->decodeResponseJson();

        $this->assertEquals(1, $responseContent['result']);
        $responseData = $responseContent['data'];

        $this->assertEquals($name, $responseData['name']);
        $this->assertEquals($description, $responseData['description']);
        $this->assertEquals($imageUrl, $responseData['image_url']);
        $this->assertEquals($tagOne->tag_name . ", " . $tagTwo->tag_name, $responseData['tags']);
        $this->assertDatabaseHas('products', [
            'name' => $name,
            'description' => $description,
            'image_url' => $imageUrl
        ]);
        $this->assertCount(0, Tag::where('tag_name', $tagOne->tag_name)->where('id', '<>', $tagOne->id)->get());
        $this->assertCount(0, Tag::where('tag_name', $tagTwo->tag_name)->where('id', '<>', $tagTwo->id)->get());
    }

    public function test_create_new_required_field_only()
    {
        $name = $this->faker->unique()->word();

        $postData = [
            'name' => $name
        ];
        $response = $this->postJson('/api/products', $postData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'image_url'
                ]
            ]);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(1, $responseContent['result']);
        $responseData = $responseContent['data'];

        $this->assertEquals($name, $responseData['name']);
        $this->assertEquals(null, $responseData['description']);
        $this->assertEquals(null, $responseData['image_url']);
        $this->assertDatabaseHas('products', [
            'name' => $name,
            'description' => null,
            'image_url' => null
        ]);
    }

    public function test_create_new_failed_no_name()
    {
        $existingProduct = Product::factory()->make();
        $existingProduct->save();

        $postData = [
        ];
        $response = $this->postJson('/api/products', $postData);

        $response->assertStatus(422);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(0, $responseContent["result"]);
        $this->assertEquals(["name is required"], $responseContent["errors"]["name"]);
    }

    public function test_create_new_failed_duplicate_name()
    {
        $existingProduct = Product::factory()->make();
        $existingProduct->save();

        $postData = [
            'name' => $existingProduct->name
        ];
        $response = $this->postJson('/api/products', $postData);

        $response->assertStatus(422);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(0, $responseContent["result"]);
        $this->assertEquals(["name must be unique"], $responseContent["errors"]["name"]);
        $this->assertCount(1, Product::get()->where('name', $existingProduct->name));
    }

    public function test_create_new_failed_invalid_url()
    {
        $name = $this->faker->unique()->word();
        $imageUrl = $this->faker->word();

        $postData = [
            'name' => $name,
            'image_url' => $imageUrl
        ];
        $response = $this->postJson('/api/products', $postData);

        $response->assertStatus(422);
        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(0, $responseContent["result"]);
        $this->assertEquals(["Image url must be a valid url"], $responseContent["errors"]["image_url"]);
        $this->assertDatabaseMissing('products', ['image_url' => $imageUrl]);
    }
}
