<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FoodRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Food;
use App\Models\Menu;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return jsonResponse
     */
    public function index()
    {
        //
        $data = Food::all();
        return $this->jsonResponse(['foods' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(FoodRequest $request)
    {
        //
        $food = new Food();
        $food->name = $request->name;
        $food->price = $request->price;
        //イメージファイルがある場合はstorageに保存
        if($request->file('file')) {
            $file_name = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs('public', $file_name);
            $food->image_file = $file_name;
        }
        $food->save();

        $data = ['message' => '登録しました。',
                 'id' => $food->id];

        return ['success' => $data];
                
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return jsonResponse
     */
    public function show($id)
    {
        //
        $data = Food::find($id);
        return $this->jsonResponse(['food' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array
     */
    public function update(FoodRequest $request, $id)
    {
        //
        $food = Food::where('id', $id)->first();
        $food->name = $request->name;
        $food->price = $request->price;

        if($request->file('file')) {
                //イメージファイルの削除
                $old_file_name = $food->image_file;
                if($old_file_name !== 'no_image.png') {
                    if(Storage::exists('public/' . $old_file_name)) {
                        Storage::delete('public/' . $old_file_name);
                    }
                }
            $file_name = time() . '.' . $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs('public', $file_name);
            $food->image_file = $file_name;
        }
        $food->save();

        $data = ['message' => '変更しました。',
                 'id' => $food->id];

        return ['success' => $data];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        //リレーションの確認後、削除
        if(!Menu::where('food_id', $id)->exists()) {
            $food = Food::where('id', $id)->first();
                //イメージファイルの削除(no_image.pngは共用の為、消さない)
                $file_name = $food->image_file;
                if($file_name !== 'no_image.png') {
                    if(Storage::exists('public/' . $file_name)) {
                        Storage::delete('public/' . $file_name);
                    }
                }
            $food->delete();
            $data = ['message' => '削除しました。'];
            return ['success' => $data];
        }else {
            $data = ['message' => "メニューデータにリレーションがあります。\nメニューデータを削除してから\n食品データを削除して下さい。"];
            return ['failure' => $data];
        }
        
    }
}
