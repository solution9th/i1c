<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\User;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class LoginController extends Controller
{
    /**
     * 方法重写自Illuminate\Foundation\Auth\AuthenticatesUsers
     */

    use RedirectsUsers, ThrottlesLogins;

    protected $redirectTo = '/home';
    protected $pid = null;
    protected $msg = '登录失败';

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request, $pid)
    {
        if (empty($pid)) {
            $this->error();
        }
        $request->session()->pull('pid');
        $request->session()->put('pid',$pid);
        $request->session()->put('url.intended', $pid);

        $project = Project::where('pid', $pid)->select('logintitle', 'logo', 'loginstart', 'loginend');
        \View::addExtension('html', 'blade');
        if ($project->exists()) {
            $project = $project->first();

            if ($project->loginstart !== NULL && (time() < strtotime($project->loginstart))) {
                return view('fail', [
                    'title'=>$project->logintitle,
                    'logo'=>$project->logo,
                    'msg' => '登录入口未开放'
                ]);
            }

            if ($project->loginend !== NULL && (time() > strtotime($project->loginend))) {
                return view('fail', [
                    'title'=>$project->logintitle,
                    'logo'=>$project->logo,
                    'msg' => '登录入口已关闭'
                ]);
            }
        } else {
            return view('404');
        }

        return response(file_get_contents(base_path('../i1c_front/views/login.html')));
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $this->pid = $request->session()->get('pid');

        if ($this->attemptLogin($request)) {
            return $this->success($this->sendLoginResponse($request));
        }

        return $this->error($this->msg);
    }


    public function authRedirect(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'pid' => 'required|string',
            'client_id' => 'required|integer',
            'redirect_uri' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

//        $pid = $request->input('pid');
        $query = [
            'client_id'     => $request->input('client_id'),
            'redirect_uri'  => $request->input('redirect_uri'),
            'response_type' => 'code',
            'scope'         => '',
        ];

        if ($request->exists('state')) {
            $query['state'] = $request->input('state');
        }

//        if ($request->user()) {
//            $email = $request->user()['email'];
//            $hasuser = Project::wherehas('users', function ($query) use ($email){
//                $query->where('email', $email);
//            })->where('pid', $pid)->exists();
//
//            if(! $hasuser) {
//                $this->logout($request);
//            }
//        }

        $this->redirectTo = env('APP_URL') . "/oauth/authorize?" . http_build_query($query);

        $request->session()->pull('pid');
        $request->session()->put('pid', $request->input('pid'));
        $request->session()->put('url.intended', $this->redirectTo);

        if ($request->user()) {
            return redirect()->away($this->redirectTo);
        }

        return response(file_get_contents(base_path('../i1c_front/views/login.html')));
    }
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request,[
            'account'  => 'required|string',
            'captcha'  => 'required|string'
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $account = $request->get('account');
        $verify = Cache::get($account);

        if ($verify == $request->get('captcha')) {
            try {
                if (! empty($this->pid)) {
                    $data = User::with(['projects' => function ($query) {
                        $query->where('pid', $this->pid);
                    }])->where('mobile', $account)
                        ->orWhere('email', $account)
                        ->select('id','email','name', 'created_at', 'updated_at')
                        ->first();

                    if (empty($data->projects->toArray())) {
                        $this->msg = '没有登录权限';

                        return false;
                    } else {
                        $this->guard()->login($data);
                        return true;
                    }
                }
            } catch (Exception $e){

            }
        }
        $this->msg = '验证码错误';
        return false;
    }


    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);
        $data = $this->authenticated($request, $this->guard()->user())
            ?: session()->pull('url.intended', $this->redirectPath());

        return $data;
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'account';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->success();
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    public function getAttributes(Request $request)
    {
        $this->pid = $request->exists('pid') ? $request->input('pid') : $request->session()->get('pid');

        return $this->success($this->getAttrs());
    }

    protected function getAttrs()
    {
        $data = Project::where('pid', $this->pid)->select('path', 'pid', 'name', 'logintitle', 'logo', 'loginfield')->first();

        return $data;
    }
}
