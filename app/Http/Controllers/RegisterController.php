<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class RegisterController extends Controller
{
    use RedirectsUsers;

    protected $pid;
    protected $msg = '注册失败';
    protected $redirectTo = '/login';


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request, $pid = null)
    {
        if (empty($pid)) {
            $this->error();
        }
        $request->session()->pull('pid');
        $request->session()->put('pid',$pid);

        $project = Project::where('pid', $pid)->select('regtitle', 'logo', 'regstart', 'regend');

        \View::addExtension('html', 'blade');
        if ($project->exists()) {
            $project = $project->first();

            if ($project->regstart !== NULL && (time() > strtotime($project->regstart))) {
                return view('fail', [
                    'title'=>$project->regtitle,
                    'logo'=>$project->logo,
                    'msg' => '注册未开放'
                ]);
            }

            if ($project->regend !== NULL && (time() > strtotime($project->regend))) {
                return view('fail', [
                    'title'=>$project->regtitle,
                    'logo'=>$project->logo,
                    'msg' => '注册入口已关闭'
                ]);
            }
        } else {
            return view('404');
        }

        return response(file_get_contents(base_path('../i1c_front/views/regist.html')));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $this->pid = $request->session()->get('pid');
        event(new Registered($user = $this->create($request->all())));

        if (! $user) {
            return $this->error($this->msg);
        }

        return $this->success();
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255',
            'mobile'     => 'required|string|max:13',
            'captcha'    => 'required|string|min:4',
            'infomation' => 'required|json'
        ]);
    }

    protected function create(array $data)
    {
        $account = request()->post('mobile');
        $verify = Cache::get($account);

        //首先验证手机验证码是否正确
        if ($verify == request()->post('captcha')) {
            $user = new User();

            //如果存在此用户,整合信息
            if ($user->where('email', $data['email'])->exists()) {
                $pid = $this->pid;

                $hasuser = User::whereHas('projects', function ($query) use ($pid) {
                    $query->where('pid', $pid);
                })->where('email', $data['email'])->exists();

                if ($hasuser) {
                    $this->msg = '已存在的邮箱';
                    return false;
                }

                $userdata = $user->where('email', $data['email'])->first()->toArray();
                $userdata['infomation'] = json_decode($userdata['infomation'], true);
                $data['infomation'] = json_decode($data['infomation'], true);
                $data = array_merge($userdata, $data);
                $data['infomation'] = json_encode($data['infomation']);
            }

            $user = User::updateOrCreate(
                [
                    'email'      => $data['email']
                ],
                [
                    'name'       => $data['name'],
                    'mobile'     => $data['mobile'],
                    'type'       => User::GENERAL
                ]);
        $user->where('email', $data['email'])->update(['infomation'=>$data['infomation']]);

            //添加关系
            if (Project::where('pid', $this->pid)->exists()) {
                $project = Project::where('pid', $this->pid)->first()->toArray();
                try {
                    $user->projects()->attach($project['id']);
                } catch (Exception $e) {

                }
            }

            return $user;
        }
        $this->msg = '验证码错误';
        return false;
    }

    public function getAttributes(Request $request)
    {
        $this->pid = $request->exists('pid') ? $request->input('pid') : $request->session()->get('pid');

        return $this->success($this->getAttrs());
    }

    protected function getAttrs()
    {
        $data = Project::where('pid', $this->pid)->select('path', 'pid', 'name', 'regtitle', 'logo', 'protocol', 'regfield')->first();

        return $data;
    }
}
