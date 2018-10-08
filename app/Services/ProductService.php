<?php
namespace App\Services;
use App\Model\Product;
use App\Model\ProductExchange;
use App\Model\AgentPrice;
use App\Model\Agent;
use Manpro\Manpro;

class ProductService extends Service{

    public function store($data)
    {
        if(Product::where('name', $data['name'])->where('status', 1)->first()) return false;
        $prices = $data['prices'];
        unset($data['prices']);
        $product = Product::create($data);
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
        if($id && $old_id != $id) return false;
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
        $manpro = new Manpro();
        $where['products.status'] = 1;
        if(isset($search['name'])) $where[] = ['name', 'like', '%'.$search['name'].'%'];
        $data = Product::select('products.*','brands.brand_name')->where($where)->leftJoin('brands','brands.id','products.brand_id')
                       ->orderBy('stock')->orderBy('brand_id')->paginate(10)->toArray();
        foreach($data['data'] as &$info){
            $prices = AgentPrice::select('agents.*','agent_prices.price')->where('product_id', $info['id'])
                                ->leftJoin('agents', 'agents.id', 'agent_prices.agent_id')->get()->toArray();
            foreach ($prices as &$price) {
                $price['price'] = $price['price']/100;
            }
            $info['prices'] = $manpro->indexArrKey($prices, 'id');
        }
        return $data;
    }

    public function read($id)
    {
        return Product::find($id)->toArray();
    }

    public function products($search)
    {
        $data = Product::select('id', 'name')->where('brand_id', $search['brand_id'])->get();
        return $data ? $data->toArray() : [];
    }
}