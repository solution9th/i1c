<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Attribute;

class AttributeController extends Controller
{
    public function list()
    {
        $data = Attribute::all();

        return $this->success($data);
    }

    public function store()
    {
        $validator = $this->validator();

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $attribute = new Attribute();
        $row = $attribute->where('name', request()->post('name'))->exists();

        if($row) {
            return $this->error('字段名称已存在');
        }
        else {
            $attribute->fill(request()->post())->save();
            return $this->success();
        }

    }

    public function update($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->fill(request()->input())->save();

        return $this->success();
    }

    public function destory($id)
    {
        Attribute::destroy($id);

        return $this->success();
    }

    private function validator()
    {
        return Validator::make(request()->post(), [
            'name'        => 'required|string',
            'display'     => 'required|string',
            'type'        => 'required|string',
            'must'        => 'required|integer'
        ]);
    }
}
