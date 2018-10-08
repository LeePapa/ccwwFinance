<?php
namespace App\Services;
use App\Model\Brand;

class BrandService extends Service{

    public function store($data)
    {
        if(Brand::where('brand_name', $data['brand_name'])->where('status', 1)->first()) return false;
        return Brand::create($data);
    }

    public function delete($id)
    {
        return Brand::where('id', $id)->update(['status'=>0]);
    }

    public function update($id, $data)
    {
        $old_id = Brand::where('brand_name', $data['brand_name'])->where('status', 1)->value('id');
        if($id && $old_id != $id) return false;
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
        $data = Brand::select('id', 'brand_name')->get();
        return $data ? $data->toArray() : [];
    }
}