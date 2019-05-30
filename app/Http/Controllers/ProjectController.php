<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function list(Request $request)
    {
        $pagesize = $request->input('pagesize') ?? 15;
        $data = Project::select('id', 'pid', 'name', 'logo', 'protocol', 'description', 'path', 'secret')
            ->orderBy('created_at', 'desc')
            ->paginate($pagesize)
            ->toArray();

        return $this->success($data);
    }

    public function listNoPage()
    {
        $data = Project::select('id', 'pid', 'name', 'path')->get();

        return $this->success($data);
    }

    /**
     * @param Request $request
     * 创建项目
     */
    public function createProject(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'name' => 'required|string',
            'path' => 'required|url',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $exists = Project::where('name', request()->input('name'))->exists();
        if($exists) {
            return $this->error('项目名称已存在');
        }

        $pid  = str_random(10);
        $data['pid'] = $pid;
        $data['secret'] = str_random(20);
        $data['user'] = app()->i1c['username'];

        $project = new Project();
        $save = $project->fill(array_merge(request()->input(), $data))->save();

        if($save) {
            return $this->success($pid);
        } else {
            return $this->error('项目创建失败');
        }
    }

    /**
     * @param Request $request
     * 更新项目
     */
    public function updateProject(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'pid'  => 'required|string',
            'name' => 'required|string',
            'path' => 'required|url',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $exists = Project::where('pid', request()->input('pid'))->exists();
        if( ! $exists) {
            return $this->error('项目不存在');
        }

        $pid  = $request->input('pid');
        $data['user'] = app()->i1c['username'];

        $project = new Project();
        $update = $project->where('pid', $pid)->update(array_merge($request->input(),$data));

        if($update) {
            return $this->success($pid);
        } else {
            return $this->error('项目创建失败');
        }
    }

    /**
     * @param $pid
     * 生成/更新 注册表单
     */
    public function createRegister(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'regfield' => 'required|json',
            'pid'      => 'required|string',
            'regtitle' => 'required|string',
            'regstart' => 'present',
            'regend'   => 'present',
        ]);

        if($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $pid = $request->input('pid');


        $regstart = $request->regstart;
        if (! empty($regstart)) {
            $regstart = date('Y-m-d H:i:s', strtotime($regstart));
        }

        $regend = $request->regend;
        if (! empty($regend)) {
            $regend = date('Y-m-d H:i:s', strtotime($regend));
        }

        Project::where('pid', $pid)->update(array_merge(['regend'=>$regend, 'regstart'=>$regstart], $request->only('regfield', 'regtitle')));
        $data = Project::where('pid', $pid)->first();

        return $this->success($data->regurl);
    }

    /**
     * @param $pid
     * 生成/更新 登录表单
     */
    public function createLogin(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'loginfield' => 'required|json',
            'pid'        => 'required|string',
            'logintitle' => 'required|string',
            'loginstart' => 'present',
            'loginend'   => 'present'
        ]);
        if($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $pid = $request->input('pid');

        $loginstart = $request->loginstart;
        if (! empty($loginstart)) {
            $loginstart = date('Y-m-d H:i:s', strtotime($loginstart));
        }

        $loginend = $request->loginend;
        if (! empty($loginend)) {
            $loginend = date('Y-m-d H:i:s', strtotime($loginend));
        }

        Project::where('pid', $pid)->update(array_merge(['loginstart' => $loginstart, 'loginend'=>$loginend], $request->only('loginfield','logintitle')));
        $data = Project::where('pid', $pid)->first();

        return $this->success($data->loginurl);
    }

    /**
     * @param $pid
     * 获取项目的详细信息
     */
    public function info($pid)
    {
        $data = Project::where('pid', $pid)->first();

        return $this->success($data);
    }

    /**
     * 获取没有注册表单的项目
     */
    public function getRegfieldNull()
    {
        $projects = Project::whereNull('regfield')->select('id', 'pid', 'name')->get();

        return $this->success($projects);
    }

    /**
     * 获取没有登录表单的项目
     */
    public function getLoginfieldNull()
    {
        $projects = Project::whereNull('loginfield')->select('id', 'pid', 'name')->get();

        return $this->success($projects);
    }

    /**
     * 检测项目名称是否存在
     * 存在返回 true ,不存在返回 false
     */
    public function existsProject()
    {
        $validator = Validator::make(request()->input(), [
            'name' => 'required|string'
        ]);

        if($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        if(Project::where('name', request()->input('name'))->exists()) {
            return $this->success('true');
        }

        return $this->success('false');
    }

    /**
     * @param Request $request
     * 获取链接项目链接
     * 包括注册链接和登录链接
     */
    public function getUrl(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'pid' => 'required|string'
        ]);

        if($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $pid = $request->input('pid');

        $project = Project::where('pid', $pid)->select('path', 'pid')->first();

        $data = [
            'regurl' => $project->regurl,
            'loginurl' => $project->loginurl
        ];

        return $this->success($data);
    }

    public function getUserlist(Request $request)
    {
        $pid = $request->input('pid');
        $pagesize = $request->input('pagesize') ?? 20;

        if ($pid) {
            $users = \App\User::whereHas('projects', function ($query) use ($pid) {
                $query->where('pid', $pid);
            });
        } else {
            $users = \App\User::has('projects');
        }

        if ($request->input('keywords')) {
            $keywords = $request->input('keywords');
            $users = $users->where('email', 'like', "%{$keywords}%")
                ->orWhere('mobile', 'like', "%{$keywords}%")
                ->orWhere('name', 'like', "%{$keywords}%");
        }

        $users = $users->with(['projects' => function ($query) {
            $query->select('pid', 'name');
        }])->paginate($pagesize);

        return $this->success($users);
    }

    /**
     * @param Request $request
     * 获取某个项目下的所有用户
     */
    public function getUsers(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'pid' => 'required|string'
        ]);

        if($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $pid = $request->input('pid');

        $users = \App\User::whereHas('projects', function ($query) use ($pid) {
            $query->where('pid', $pid);
        })->select('id','email','name','mobile','created_at','updated_at')->get();

        return $this->success($users);
    }
}
