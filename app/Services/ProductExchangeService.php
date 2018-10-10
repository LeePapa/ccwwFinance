<?php
namespace App\Services;
use App\Model\Product;
use App\Model\ProductExchange;
use App\Model\ExchangeDetail;
use App\Model\AgentPrice;
use App\Model\Agent;
use App\Model\User;
use DB;

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
                'receive_id' =>$data['receive_id'],
                'user_id'    =>$user->id,
            ];
            ExchangeDetail::create($temp);
            User::where('id', $temp['receive_id'])->increment('expend', $temp['total']);
            User::where('id', $user->id)->increment('income', $temp['total']);
        }
        return true;
    }

    public function show($search = [])
    {
        $btime = date('Y-m-d H:i:s', strtotime($search['month']));
        $etime = date('Y-m-d H:i:s', strtotime($search['month'] . ' +1 months') -1);

        $user_id = auth()->user()->id;
        $data = ProductExchange::select('product_exchanges.*', 'users.username', 'users.phone')
                                ->where('product_exchanges.status', 1)->where('user_id', $user_id)
                                ->whereBetween('product_exchanges.created_at', [$btime, $etime])
                                ->leftJoin('users', 'product_exchanges.receive_id', 'users.id')->paginate(10)->toArray();
        foreach ($data['data'] as &$info) {
            $info['total'] = $info['profit'] = 0;
            $info['details'] = $this->exchangeDetail($info['id']);
            foreach ($info['details'] as $v) {
                $info['total'] += $v['total'];
                $info['profit'] += $v['profit'];
            }
        }
        $dataTotal = [
            'total' =>ExchangeDetail::where('user_id', $user_id)->where('status', 1)
                                    ->whereBetween('created_at', [$btime, $etime])->sum('total')/100,
            'profit'=>ExchangeDetail::where('user_id', $user_id)->where('status', 1)
                                    ->whereBetween('created_at', [$btime, $etime])->sum('profit')/100,
        ];
        $dataTotal['user_total'] = $dataTotal['total'] - $dataTotal['profit'];
        $data['dataTotal'] = $dataTotal;
        return $data;
    }

    public function exchangeDetail($exchange_id)
    {
        $data = ExchangeDetail::select('exchange_details.*', 'products.name as product_name', 'brands.brand_name')->where('exchange_id', $exchange_id)
                              ->leftJoin('products', 'products.id', 'exchange_details.product_id')
                              ->leftJoin('brands', 'brands.id', 'exchange_details.brand_id')->get()->toArray();
        foreach ($data as &$info) {
            foreach (['price', 'total', 'profit', 'user_price'] as $v) {
                $info[$v] = $info[$v]/100;
            }
        }
        return $data;
    }

    public function delete($id)
    {
        return ProductExchange::where('id', $id)->update([
            'status'=>0
        ]) && ExchangeDetail::where('order_id', $id)->update([
            'status'=>0
        ]);
    }

    /**
     * 品牌统计
     * @param  array $data 时间限制
     * @param  string $type 统计类型 day/week/month
     * @return array
     */
    public function brandStatistics($data, $type)
    {
        if($type == 'day'){
            $format = 'Y-m-d';
            $interval = '+1 days';
        }
        if($type == 'month'){
            $format = 'Y-m';
            $interval = '+1 months';
        }
        $date_arr = $this->manpro->betweenDates($data['start_date'], $data['end_date'], $format, $interval);

    }
    /**
     * 月份统计
     * @param  string $month 几月份
     * @return array        统计结果
     */
    public function monthStatistics($month)
    {
        $btime = date('Y-m-d H:i:s', strtotime($month));
        $etime = date('Y-m-d H:i:s', strtotime($month . ' +1 months') -1);

        $raw = DB::raw('sum(product_num) as product_num,
                        sum(profit) as profit,
                        sum(total) as total
                        ');
        $data = ExchangeDetail::select($raw, 'receive_id', 'product_id', 'brand_id')->whereBetween('created_at', [$btime, $etime])->where('status', 1)->where('user_id', auth()->user()->id)->groupBy('receive_id')->groupBy('product_id')->get()->toArray();
        $res = [];
        foreach ($data as &$info) {
            $res[$info['receive_id']][] = $info;
        }
        return $res;
    }
}