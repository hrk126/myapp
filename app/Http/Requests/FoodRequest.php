<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        if($this->path() == 'api/food') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:foods,name,' . $this->id . ',id',
            'price' => 'required|integer',
            'file' => 'nullable|image'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '名前を入力して下さい',
            'name.unique' => '登録済みの名前です',
            'price.required' => '金額を入力して下さい',
            'price.integer' => '整数を入力して下さい',
            'file.image' => '画像ファイルを選択して下さい'
        ];
    }
}
