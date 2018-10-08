<?php
namespace App\Services;
use App\Model\User;
use App\Model\Agent;

class UserService extends Service{

    public function store($data)
    {
        if(User::where('phone', $data['phone'])->where('status', 1)->first()) return false;
        if(isset($data['password'])) $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return User::create($data);
    }

    public function delete($id)
    {
        return User::where('id', $id)->update(['status'=>0]);
    }

    public function update($id, $data)
    {
        $old_id = User::where('phone', $data['phone'])->where('status', 1)->value('id');
        if($id && $old_id != $id) return false;
        return User::where('id', $id)->update($data);
    }

    public function show($search = [])
    {
        $where[] = ['users.status', '=', 1];
        if(isset($search['username'])) $where[] = ['username', 'like', '%'.$search['username'].'%'];
        return User::select('users.*','agents.name as agent_name')->where($where)
                ->leftJoin('agents', 'agents.id', 'users.agent_id')->paginate(10)->toArray();
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
                $this->update($info->id,['last_login_time'=>date('Y-m-d H:i:s')]);
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
}