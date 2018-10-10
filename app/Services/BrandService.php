<?php
namespace App\Services;
use App\Model\Brand;
use App\Model\Product;

class BrandService extends Service{

    public function store($data)
    {
        if(Brand::where('brand_name', $data['brand_name'])->where('status', 1)->first()) return false;
        return Brand::create($data);
    }

    public function delete($id)
    {
        if(Product::where('brand_id', $id)->where('status', 1)->first()) return false;
        return Brand::where('id', $id)->update(['status'=>0]);
    }

    public function update($id, $data)
    {
        $old_id = Brand::where('brand_name', $data['brand_name'])->where('status', 1)->value('id');
        if($old_id && $old_id != $id) return false;
        return Brand::where('id', $id)->update($data);
    }

    public function show($search = [])
    {
        $where['status'] = 1;
        if(isset($search['name'])) $where[] = ['brand_name', 'like', '%'.$search['name'].'%'];
        return Brand::where($where)->paginate(10)->toArray();
    }

    public function read($id)
    {
        return Brand::find($id)->toArray();
    }

    public function brands()
    {
        $data = Brand::select('id', 'brand_name')->where('status', 1)->get();
        return $data ? $data->toArray() : [];
    }
}