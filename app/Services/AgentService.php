<?php
namespace App\Services;
use App\Model\Agent;
use App\Model\User;

class AgentService extends Service{

    public function store($data)
    {
        if(Agent::where('name', $data['name'])->where('status', 1)->first()) return false;
        return Agent::create($data);
    }

    public function delete($id)
    {
        if(User::where('agent_id', $id)->where('status', 1)->first()) return false;
        return Agent::where('id', $id)->update(['status'=>0]);
    }

    public function update($id, $data)
    {
        $old_id = Agent::where('name', $data['name'])->where('status', 1)->value('id');
        if($old_id && $old_id != $id) return false;
        return Agent::where('id', $id)->update($data);
    }

    public function show($search = [])
    {
        $where['status'] = 1;
        if(isset($search['name'])) $where[] = ['name', 'like', '%'.$search['name'].'%'];
        return Agent::where($where)->paginate(10)->toArray();
    }

    public function read($id)
    {
        return Agent::find($id)->toArray();
    }

    public function agents()
    {
        $data = Agent::select('id', 'name')->where('status', 1)->get();
        return $data ? $data->toArray() : [];
    }
}