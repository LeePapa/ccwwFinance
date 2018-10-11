<?php
namespace App\Services;
use App\Model\Product;
use App\Model\ProductExchange;
use App\Model\AgentPrice;
use App\Model\Agent;
use App\Model\User;

class ProductService extends Service{

    public function store($data)
    {
        if(Product::where('name', $data['name'])->where('status', 1)->first()) return false;
        $prices = $data['prices'];
        unset($data['prices']);
        $product = Product::create($data);
        $data['user_id'] = auth()->user()->id;
        if($product){
            foreach ($prices as $id=>$price) {
                AgentPrice::create([
                    'agent_id'  =>$id,
                    'product_id'=>$product->id,
                    'price'     => $price*100
                ]);
            }
        }
        return true;
    }

    public function delete($id)
    {
        return Product::where('id', $id)->update(['status'=>0]);
    }

    public function update($id, $data)
    {
        $old_id = Product::where('name', $data['name'])->where('status', 1)->value('id');
        if($old_id && $old_id != $id) return false;
        $prices = $data['prices'];
        unset($data['prices']);
        foreach ($prices as $agent_id=>$price) {
            if(AgentPrice::where('agent_id', $agent_id)->where('product_id', $id)->first()){
                AgentPrice::where('agent_id', $agent_id)->where('product_id', $id)->update([
                    'price'     => $price*100
                ]);
            }else{
                AgentPrice::create([
                    'agent_id'  =>$agent_id,
                    'product_id'=>$id,
                    'price'     => $price*100
                ]);
            }
        }
        return Product::where('id', $id)->update($data);
    }

    public function show($search = [])
    {
        $where['products.status'] = 1;
        if(isset($search['name'])) $where[] = ['name', 'like', '%'.$search['name'].'%'];
        if(isset($search['brand_id'])) $where[] = ['brand_id', $search['brand_id']];
        $data = Product::select('products.*','brands.brand_name')->where($where)->leftJoin('brands','brands.id','products.brand_id')
                       ->orderBy('stock')->orderBy('brand_id')->paginate(10)->toArray();
        foreach($data['data'] as &$info){
            $info['prices'] = $this->prices($info['id']);
            foreach ($info['prices'] as &$v) {
                $v = $v['price'];
            }
        }
        return $data;
    }

    public function read($id)
    {
        $data = Product::find($id)->toArray();
        $data['prices'] = $this->prices($id);
        $data['userPrice'] = $data['prices'][auth()->user()->agent_id]['price'];
        return $data;
    }

    public function products($search)
    {
        $data = Product::select('id', 'name')->where('brand_id', $search['brand_id'])->where('status', 1)->get();
        return $data ? $data->toArray() : [];
    }

    public function prices($product_id)
    {
        $prices = AgentPrice::select('agents.*','agent_prices.price')->where('product_id', $product_id)
                                ->leftJoin('agents', 'agents.id', 'agent_prices.agent_id')->get()->toArray();
        foreach ($prices as &$price) {
            $price['price'] = $price['price']/100;
        }
        $prices = $this->manpro->indexArrKey($prices, 'id');
        return $prices;
    }

    public function inbound($data, $id)
    {
        if(Product::where('id', $id)->increment('stock', $data['stock'])){
            $user  = auth()->user();
            $price = AgentPrice::where('product_id', $id)->where('agent_id', $user->agent_id)->value('price');
            User::where('id', $user->id)->increment('expend', $data['stock']*$price);
            return true;
        }
        return false;

    }
}