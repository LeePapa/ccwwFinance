<?php
namespace App\Services;
use App\Model\Product;
use App\Model\ProductExchange;
use App\Model\ExchangeDetail;
use App\Model\AgentPrice;
use App\Model\Agent;
use Manpro\Manpro;

class ProductExchangeService extends Service{

    public function store($data)
    {
        $flowid = date('YmdHis').rand(10000,99999);
        $user   = auth()->user();
        $exchange = ProductExchange::create([
            'user_id'    =>$user->id,
            'flowid'     =>$flowid,
            'receive_id' =>$data['receive_id'],
        ]);
        foreach ($data['products'] as $product) {
            if(!$product['product_id']) continue;
            $stock = Product::where('id', $product['product_id'])->value('stock');
            if($stock < $product['product_num']) return false;      //检测库存
            Product::where('id', $product['product_id'])->decrement('stock', $product['product_num']);
            $total  = $product['price']*$product['product_num']*100;
            $profit = $total - $product['userPrice']*$product['product_num']*100;
            $temp = [
                'exchange_id'=>$exchange->id,
                'product_id' =>$product['product_id'],
                'product_num'=>$product['product_num'],
                'price'      =>$product['price']*100,
                'total'      =>$total,
                'user_price' =>$product['userPrice']*100,
                'profit'     =>$profit,
                'brand_id'   =>$product['brand_id'],
            ];
            ExchangeDetail::create($temp);
        }
        return true;
    }

    public function show()
    {
        $data = ProductExchange::select('product_exchanges.*', 'users.username', 'users.phone')
                                ->leftJoin('users', 'product_exchanges.receive_id', 'users.id')->paginate(10)->toArray();
        foreach ($data['data'] as &$info) {
            $info['total'] = $info['profit'] = 0;
            $info['details'] = $this->exchangeDetail($info['id']);
            foreach ($info['details'] as $v) {
                $info['total'] += $v['total'];
                $info['profit'] += $v['profit'];
            }
        }
        return $data;
    }

    public function exchangeDetail($exchange_id)
    {
        $data = ExchangeDetail::select('exchange_details.*', 'products.name as product_name', 'brands.brand_name')->where('exchange_id', $exchange_id)
                              ->leftJoin('products', 'products.id', 'exchange_details.product_id')
                              ->leftJoin('brands', 'brands.id', 'exchange_details.brand_id')->get()->toArray();
        foreach ($data as &$info) {
            foreach (['price', 'total', 'profit'] as $v) {
                $info[$v] = $info[$v]/100;
            }
        }
        return $data;
    }
}