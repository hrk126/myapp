<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;
use App\Models\Food;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FoodRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class FoodTest extends TestCase
{
    use RefreshDatabase;
    /**
     * データベーステスト
     *
     * @return void
     */
    public function test_Database()
    {
        //データベースのカラム確認
        $this->assertTrue(
            Schema::hasColumns('foods', [
                'id', 'name', 'price', 'image_file'
            ]),
            1
        );

        //データベースに登録できる
        $food = new Food();
        $food->name = '定食1';
        $food->price = 100;
        $food->image_file = 'sample.jpg';
        $saveFood = $food->save();
        $this->assertTrue($saveFood);

        //image_fileのデフォルト値が'no_image.png'となる
        Food::factory()->state([
            'name' => '定食2',
            'price' => 200,
        ])->create();
        $food_image = [
            'image_file' => 'no_image.png',
        ];
        $this->assertDatabaseHas('foods', $food_image);

    }

    /**
     * リクエストのバリデーションテスト
     * @param array 項目名
     * @param array 値
     * @param boolean 期待値(true:OK、false:NG)
     * @dataProvider dataproviderRequest
     * @return void
     */
    public function test_Request($item, $value, $expect)
    {
        Food::factory()->state([
            'name' => '重複',
            'price' => 100,
        ])->create();
        $data = array_combine($item, $value);
        $request = new FoodRequest();
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataproviderRequest()
    {
        $dummy_image = UploadedFile::fake()->image('dummy.jpg');
        $dummy_pdf = UploadedFile::fake()->create('dummy.pdf');
        

        return [
            'OK' => [
                ['name', 'price', 'file'],
                ['定食', 100, $dummy_image],
                true
            ],
            'name必須エラー' => [
                ['name', 'price', 'file'],
                ['', 100, ''],
                false
            ],
            'nameユニークエラー' => [
                ['name', 'price', 'file'],
                ['重複', 100, ''],
                false
            ],
            'price必須エラー' => [
                ['name', 'price', 'file'],
                ['定食', '',''],
                false
            ],
            'price整数値エラー' => [
                ['name', 'price', 'file'],
                ['定食', 1.5,''],
                false
            ],
            'file形式エラー' => [
                ['name', 'price', 'file'],
                ['定食', 100, $dummy_pdf],
                false
            ],
        ];
    }

    /**
     * コントローラーテスト
     *
     *
     *@return void
     */
    public function test_FoodController()
    {
        //確認データ作成
        $data = [
            'name' => '定食',
            'price' => 100
        ];
        $id = Food::factory()->create($data)->id;

        //get(/api/food)
        $response = $this->get('/api/food');
        $response->assertOk();
        $response->assertJsonFragment($data);

        //post(/api/food)
        $dummy_image = UploadedFile::fake()->image('dummy.jpg');
        $post_data = [
            'name' => '定食2',
            'price' => 200,
            'file' => $dummy_image
        ];
        $response = $this->post('/api/food', $post_data);
        $response->assertJson([
                    'success' => ['message' => '登録しました。']
                   ]);
        $post_data_id = json_decode($response->content(), true)['success']['id'];
        $food = Food::find($post_data_id);
        $expect = [
            'name' => '定食2',
            'price' => 200
        ];
        $result = [
            'name' => $food->name,
            'price' => $food->price
        ];
        $this->assertEquals($expect, $result);
        $this->assertMatchesRegularExpression('/dummy.jpg/', $food->image_file);
        Storage::disk('public')->assertExists($food->image_file);
        Storage::delete('public/' . $food->image_file);
        Storage::disk('public')->assertMissing($food->image_file);

        //get(/api/food/{id})
        $response = $this->get('/api/food/' . $id);
        $response->assertJsonFragment($data);

        //put(/api/food/{id})
        $put_data = [
            'name' => '定食3',
            'price' => 300
        ];
        $response = $this->put('/api/food/' . $id, $put_data);
        $response->assertJson([
                    'success' => ['message' => '変更しました。']
                   ]);
        $food = Food::find($id);
        $result = [
            'name' => $food->name,
            'price' => $food->price
        ];
        $this->assertEquals($put_data, $result);

        //delete(/api/food/{id})
        $food = Food::find($id);
        $response = $this->delete('/api/food/' . $id);
        $response->assertJson([
                    'success' => ['message' => '削除しました。']
                  ]);
        $this->assertDeleted($food);

    }
}
