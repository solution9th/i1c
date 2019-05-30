<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $pagesize = $request->input('pagesize') ?? 15;
        $user = new User();
        $data = $user->with(['projects' => function ($query){
            $query->select('pid', 'name');
        }])->paginate($pagesize);

        return $this->success($data);
    }

    public function users()
    {
        $user = new User();
        $data = $user->select('id', 'email', 'mobile', 'name')->get();

        return $this->success($data);
    }

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'pid'        => 'required|string',
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255',
            'mobile'     => 'required|string|max:13',
            'infomation' => 'required|json'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $email = $request->input('email');
        $pid   = $request->input('pid');

        $user = new User();

        //如果存在此用户,整合信息
        if ($user->where('email', $email)->exists()) {

            $hasuser = User::whereHas('projects', function ($query) use ($pid) {
                $query->where('pid', $pid);
            })->where('email', $email)->exists();

            if ($hasuser) {
                return $this->error('此项目已存在此用户');
            }

            $userdata = $user->where('email', $email)->first()->toArray();
            $userdata['infomation'] = json_decode($userdata['infomation'], true);
            $data['infomation'] = json_decode($request->input('infomation'), true);
            $data = array_merge($userdata, $data);
            $data['infomation'] = json_encode($request->input('infomation'));
        }

        $user = User::updateOrCreate(
            [
                'email'      => $email
            ],
            [
                'name'       => $request->input('name'),
                'mobile'     => $request->input('mobile'),
                'type'       => User::GENERAL
            ]);
        $user->where('email', $email)->update(['infomation'=>$request->input('infomation')]);

        //添加关系
        if (Project::where('pid', $pid)->exists()) {
            $project = Project::where('pid', $pid)->first()->toArray();
            try {
                $user->projects()->attach($project['id']);
            } catch (Exception $e) {

            }
        }

        return $this->success();
    }


    public function info(Request $request)
    {
        $validator = Validator::make($request->input(),[
            'userid' => 'required|string',
            'pid'    => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $userid = $request->input('userid');
        $pid = $request->input('pid');

        $user = new User();
        $data = $user->with(['projects'=>function($query) use ($pid){
            $query->where('pid', $pid);
        }])->where('id', $userid)->get();

        return $this->success($data);
    }

    public function existsUser()
    {
        $validator = Validator::make(request()->input(),[
            'account' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $account = request()->input('account');
        if (User::where('email', $account)->orWhere('mobile', $account)->exists()) {
            return $this->success(true);
        }
        return $this->error(false);
    }

    public function nowUser(Request $request)
    {
        return $this->success(Auth::guard()->user());
    }

    public function nowAdmin(Request $request)
    {
        return $this->success(Auth::guard('admin')->user());
    }

    public function export(Request $request)
    {
        $validator = Validator::make($request->input(),[
            'pid' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

    }
}
