<?php
namespace App\Services;
use App\Model\User;
use App\Model\Agent;

class UserService extends Service{

    public function store($data)
    {
        if(User::where('phone', $data['phone'])->where('status', 1)->first()) return false;
        if(isset($data['password'])) $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['pid'] = auth()->user()->id;
        return User::create($data);
    }

    public function delete($id)
    {
        return User::where('id', $id)->update(['status'=>0]);
    }

    public function update($id, $data)
    {
        $old_id = User::where('phone', $data['phone'])->where('status', 1)->value('id');
        if($old_id && $old_id != $id) return false;
        return User::where('id', $id)->update($data);
    }

    public function show($search = [])
    {
        $where[] = ['users.status', '=', 1];
        $user_id = auth()->user()->id;
        if(isset($search['weixin'])) $where[] = ['weixin', 'like', '%'.$search['weixin'].'%'];
        if($search['agent_id']) $where[] = ['agent_id',$search['agent_id']];
        $data =  User::select('users.*','agents.name as agent_name')->where($where)->where('pid', $user_id)->orWhere('users.id', $user_id)
                ->leftJoin('agents', 'agents.id', 'users.agent_id')->paginate(10)->toArray();
        foreach ($data['data'] as &$info) {
            $info['can_del'] = $info['id'] == $user_id ? false : true;
            $info['expend']  = $info['expend'] / 100;
            $info['income']  = $info['income'] / 100;
            $info['profit']  = $info['income'] - $info['expend'];
        }
        return $data;
    }

    public function read($id)
    {
        $data = User::select('users.*','agents.name as agent_name')->leftJoin('agents', 'agents.id', 'user.agent_id')->first();
        return $data ? $data->toArray() : [];
    }

    public function login($data)
    {
        $info = User::where('phone', $data['phone'])->first();
        if($info){
            if(password_verify($data['password'],$info->password)){
                User::where('id', $info->id)->update(['last_login_time'=>date('Y-m-d H:i:s')]);
                auth()->login($info);
                return $info;
            }
        }
        return false;
    }

    public function loginout()
    {
        auth()->logout();
    }

    public function users($data = [])
    {
        return User::select('username', 'phone', 'weixin','users.id', 'agents.name as agent_name')->where('users.status', 1)
                   ->leftJoin('agents', 'users.agent_id', 'agents.id')
                   ->where('pid', auth()->user()->id)->get()->toArray();
    }
}