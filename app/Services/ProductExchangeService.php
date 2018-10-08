<?php
namespace App\Services;
use App\Model\Product;
use App\Model\ProductExchange;
use App\Model\AgentPrice;
use App\Model\Agent;
use Manpro\Manpro;

class ProductExchangeService extends Service{

    public function store($data)
    {
        $flowid = date('YmdHis').rand(10000,99999);
        foreach ($data['product'] as $product) {
            $stock = Product::where('id', $product['product_id'])->value('stock');
            if($stock < $product['product_num']) return false;      //检测库存
            Product::where('id', $product['product_id'])->decremen('stock', $product['product_num']);
            $temp = [
                'flowid'     =>$flowid,
                'producti_id'=>$product['product_id'],
                'num'        =>$product['product_num'],
                'price'      =>$product['price'],
                'total'      =>$product['price']*$product['num'],
                'receive_id' =>$data['receive_id'],
            ];
            ProductExchange::create($temp);
        }
        return true;
    }

    public function show()
    {
        return ProductExchange::select('product_exchanges.*', 'products.name as product_name', 'brands.brand_name')
                               ->leftJoin('products', 'product_exchanges.product_id', 'products.id')
                               ->leftJoin('brands', 'brands.id', 'products.brand_id')->paginate(10)->toArray();
    }
}