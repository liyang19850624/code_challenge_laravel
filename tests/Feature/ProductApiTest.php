<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Tag;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get()
    {
        $product = Product::factory()->make();
        $product->save();
        $productTagOne = Tag::factory()->make();
        $productTagOne->save();
        $productTagTwo = Tag::factory()->make();
        $productTagTwo->save();
        $product->tags()->attach($productTagOne);
        $product->tags()->attach($productTagTwo);

        $response = $this->get('/api/product/' . $product->id);

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
        $this->assertEquals($product->id, $responseData['id']);
        $this->assertEquals($product->name, $responseData['name']);
        $this->assertEquals($product->description, $responseData['description']);
        $this->assertEquals($product->image_url, $responseData['image_url']);
        $this->assertEquals($productTagOne->tag_name . ", " . $productTagTwo->tag_name, $responseData['tags']);
    }

    public function test_get_fail_id_not_found()
    {
        $response = $this->get('/api/product/' . $this->faker->randomNumber());

        $response->assertStatus(422);

        $responseContent = $response->decodeResponseJson();

        $this->assertEquals(0, $responseContent["result"]);
        $this->assertEquals(["Cannot find product"], $responseContent["errors"]["id"]);
    }

    public function test_update()
    {
        $product = Product::factory()->make();
        $product->save();
        $tagExistUnchange = Tag::factory()->make();
        $tagExistUnchange->save();
        $tagExistNeedToRemove = Tag::factory()->make();
        $tagExistNeedToRemove->save();
        $product->tags()->attach($tagExistUnchange);
        $product->tags()->attach($tagExistNeedToRemove);

        $tagNameNotExistNeedToAdd = $this->faker->unique()->word();

        $data = [
            'name' => $product->name,
            'tags' => $tagExistUnchange->tag_name . ", " . $tagNameNotExistNeedToAdd
        ];

        $response = $this->patch('/api/product/' . $product->id, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "result",
                "data" => [
                    'id',
                    'name',
                    'description',
                    'image_url',
                    'tags'
                ]
            ]);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(1, $responseContent["result"]);
        $this->assertEquals($product->id, $responseContent["data"]["id"]);
        $this->assertEquals($product->name, $responseContent["data"]["name"]);
        $this->assertEquals($product->description, $responseContent["data"]["description"]);
        $this->assertEquals($product->image_url, $responseContent["data"]["image_url"]);
        $this->assertEquals($tagExistUnchange->tag_name . ", " . $tagNameNotExistNeedToAdd, $responseContent["data"]["tags"]);

        $this->assertDatabaseHas('tags', ['id' => $tagExistUnchange->id]);
        $this->assertDatabaseHas('tags', ['id' => $tagExistNeedToRemove->id]);
        $this->assertDatabaseHas('tags', ['tag_name' => $tagNameNotExistNeedToAdd]);

        $tags = $product->tags()->pluck('tag_name')->toArray();
        $this->assertCount(2, $tags);
        $this->assertEquals($tagExistUnchange->tag_name, $tags[0]);
        $this->assertEquals($tagNameNotExistNeedToAdd, $tags[1]);
    }

    public function test_update_required_field_only()
    {
        $product = Product::factory()->make();
        $product->save();
        $newProductName =  $this->faker->word();

        $data = [
            'name' => $newProductName
        ];

        $response = $this->patch('/api/product/' . $product->id, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "result",
                "data" => [
                    'id',
                    'name',
                    'description',
                    'image_url',
                    'tags'
                ]
            ]);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(1, $responseContent["result"]);
        $this->assertEquals($product->id, $responseContent["data"]['id']);
        $this->assertEquals($newProductName, $responseContent["data"]["name"]);
        $this->assertEquals($product->description, $responseContent["data"]["description"]);
        $this->assertEquals($product->image_url, $responseContent["data"]["image_url"]);
        $this->assertEquals("", $responseContent["data"]["tags"]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $newProductName
        ]);
    }

    public function test_update_name_unchanged()
    {
        $product = Product::factory()->make();
        $product->save();

        $data = [
            'name' => $product->name
        ];

        $response = $this->patch('/api/product/' . $product->id, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "result",
                "data" => [
                    'id',
                    'name',
                    'description',
                    'image_url',
                    'tags'
                ]
            ]);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(1, $responseContent["result"]);
        $this->assertEquals($product->id, $responseContent["data"]['id']);
        $this->assertEquals($product->name, $responseContent["data"]["name"]);
        $this->assertEquals($product->description, $responseContent["data"]["description"]);
        $this->assertEquals($product->image_url, $responseContent["data"]["image_url"]);
        $this->assertEquals("", $responseContent["data"]["tags"]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name
        ]);
    }


    public function test_update_fail_duplicate_name()
    {
        $existingProduct = Product::factory()->make();
        $existingProduct->save();

        $productToUpdate = Product::factory()->make();
        $productToUpdate->save();

        $data = [
            'name' => $existingProduct->name
        ];
        $response = $this->patch('/api/product/' . $productToUpdate->id, $data);

        $response->assertStatus(422);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(0, $responseContent["result"]);
        $this->assertEquals(["name must be unique"], $responseContent["errors"]["name"]);
        $this->assertDatabaseHas('products', ['id' => $existingProduct->id, 'name' => $existingProduct->name]);
        $this->assertDatabaseHas('products', ['id' => $productToUpdate->id, 'name' => $productToUpdate->name]);
    }

    public function test_update_fail_invalid_image_url()
    {
        $product = Product::factory()->make();
        $product->save();

        $data = [
            'name' => $product->name,
            'image_url' => $this->faker()->word()
        ];
        $response = $this->patch('/api/product/' . $product->id, $data);

        $response->assertStatus(422);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(0, $responseContent["result"]);
        $this->assertEquals(["Image url must be a valid url"], $responseContent["errors"]["image_url"]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'image_url' => $product->image_url]);
    }

    public function test_update_fail_no_name()
    {
        $product = Product::factory()->make();
        $product->save();

        $data = [];
        $response = $this->patch('/api/product/' . $product->id, $data);

        $response->assertStatus(422);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(0, $responseContent["result"]);
        $this->assertEquals(["name is required"], $responseContent["errors"]["name"]);
    }

    public function test_update_fail_id_not_found()
    {
        $data = [
            'name' => $this->faker->word()
        ];
        $response = $this->patch('/api/product/' . $this->faker->randomNumber(), $data);

        $response->assertStatus(422);

        $responseContent = $response->decodeResponseJson();

        $this->assertEquals(0, $responseContent["result"]);
        $this->assertEquals(["Cannot find product"], $responseContent["errors"]["id"]);
    }

    public function test_delete()
    {
        $product = Product::factory()->make();
        $product->save();
        $productTagOne = Tag::factory()->make();
        $productTagOne->save();
        $productTagTwo = Tag::factory()->make();
        $productTagTwo->save();
        $product->tags()->attach($productTagOne);
        $product->tags()->attach($productTagTwo);

        $response = $this->delete('/api/product/' . $product->id);

        $response->assertStatus(200)
            ->assertJsonStructure(["result"]);

        $responseContent = $response->decodeResponseJson();
        $this->assertEquals(1, $responseContent["result"]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertCount(0, $productTagOne->products()->get());
        $this->assertCount(0, $productTagTwo->products()->get());
    }

    public function test_delete_fail_id_not_found()
    {
        $response = $this->delete('/api/product/' . $this->faker->randomNumber());

        $response->assertStatus(422);

        $responseContent = $response->decodeResponseJson();

        $this->assertEquals(0, $responseContent["result"]);
        $this->assertEquals(["Cannot find product"], $responseContent["errors"]["id"]);
    }
}
