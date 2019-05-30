<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Mail;
use App\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Qcloud\Sms\SmsSingleSender;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CommonController extends Controller
{

    public function sendCaptcha(Request $request)
    {
        $account = $request->input('account');

        if ($this->isEmail($account)) {
            return $this->sendEmail($request);
        } else {
            return $this->sendSms($request);
        }
    }


    protected function sendSms(Request $request)
    {
        $appid      = config('sms.appid');
        $appkey     = config('sms.appkey');
        $smsSign    = config('sms.smssign');
        $templateId = config('sms.templayteid');
        $captcha    = mt_rand(10000,99999);
        $mobile     = $request->get('account');

        if (empty($mobile)) return $this->error();

        try {
            $ssender = new SmsSingleSender($appid, $appkey);
            $params = [$captcha];
            $result = $ssender->sendWithParam("86", $mobile, $templateId, $params, $smsSign, "", "");
            $result = json_decode($result, true);

            if ($result['errmsg'] == 'OK') {
                Cache::put($mobile, $captcha, 5);

                return $this->success('发送成功');
            }

            return $this->error('发送失败');

        } catch(\Exception $e) {
        }
    }

    protected function sendEmail(Request $request)
    {
        $email   = $request->get('account');
        $pid     = $request->get('pid');

        $result = Project::where('pid', $pid)->select('name')->first();

        $from = $project = $result->name;

        $captcha = mt_rand(10000,99999);

        $flag = Mail::send('emails.captcha', ['captcha'=>$captcha, 'project'=>$project, 'email'=>$email], function ($message) use ($from, $project, $email) {
            $message->from(config('mail.from.address'), $from);
            $message->to([$email])->subject("{$project}验证码");
        });

        if (!$flag) {
            Cache::put($email, $captcha, 5);
            return $this->success('发送成功');
        } else {
            return $this->error();
        }
    }

    protected function isEmail($account){
        $mode = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
        if (preg_match($mode, $account)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 上传图片通用方法
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadImg(Request $request)
    {
        $file = $request->file('pic');

        if ($file->isValid()) {
            $clientName = $file->getClientOriginalName();
            $entension  = $file->getClientOriginalExtension();
            $path       = $file->getRealPath();

            $newName    = md5(date("Y-m-d H:i:s").$clientName).".".$entension;

            $boolean = Storage::disk('uploadimg')->put($newName, file_get_contents($path));

            if ($boolean) {
                return $this->success('/storage/img/'.$newName);
            } else {
                return $this->error('上传失败');
            }
        }
    }

    /**
     * 上传Logo
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadLogo(Request $request)
    {
        $file = $request->file('logo');

        if ($file->isValid()) {
            $clientName = $file->getClientOriginalName();
            $entension  = $file->getClientOriginalExtension();
            $path       = $file->getRealPath();

            $newName    = md5(date("Y-m-d H:i:s").$clientName).".".$entension;

            $boolean = Storage::disk('logo')->put($newName, file_get_contents($path));

            if ($boolean) {
                return $this->success('/storage/logo/'.$newName);
            } else {
                return $this->error('上传失败');
            }
        }
    }

    /**
     * 上传协议
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadProtocol(Request $request)
    {
        $file = $request->file('protocol');

        if ($file->isValid()) {
            $clientName = $file->getClientOriginalName();
            $entension  = $file->getClientOriginalExtension();
            $path       = $file->getRealPath();

            $newName    = md5(date("Y-m-d H:i:s").$clientName).".".$entension;

            $boolean = Storage::disk('protocol')->put($newName, file_get_contents($path));

            if ($boolean) {
                return $this->success($request->getSchemeAndHttpHost().'/storage/doc/'.$newName);
            } else {
                return $this->error('上传失败');
            }
        }
    }

    public function exportExcel(Request $request)
    {
        $validator = Validator::make($request->input(),[
            'pid' => 'required|string',
            'start' => 'present',
            'end'   => 'present'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $pid   = $request->pid;
        $start = $request->start;
        $end   = $request->end;

        $user = new User();

        if (! empty($start)) {
            $start = date('Y-m-d H:i:s', strtotime($start));
            $user = $user->where('updated_at', '>=', $start);
        }

        if (!empty($end)) {
            $end = date('Y-m-d H:i:s', strtotime($end));
            $user = $user->where('updated_at', '<=', $end);
        }

        $users = $user->wherehas('projects' , function($query) use ($pid) {
            $query->where('pid', $pid);
        })->orderBy('id', 'asc')->select('name', 'email', 'mobile', 'infomation')->get()->toArray();

        $project = Project::where('pid', $pid)->select('name','regfield')->first();
        $fields = array_column($project->regfield, 'display', 'name');
        unset($fields['photo']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($project->name);
        $row = 1;
        $sheet->fromArray(array_values(array_values($fields)), NULL, "A{$row}");

        foreach ($users as $key => $user) {
            $row++;
            $data['name'] = $user['name'];
            $data['mobile'] = $user['mobile'];
            $data['email'] = $user['email'];
            $infomation = json_decode($user['infomation'], true);

            foreach ($infomation as $key => $value) {
                if (is_array($value)) {
                    $infomation[$key] = implode($infomation[$key], ':');
                }
            }

            $data = array_merge($fields, $data, $infomation);

            unset($data['photo']);

            $sheet->fromArray(array_values($data), NULL, "A{$row}");
        }

        $writer = new Xlsx($spreadsheet);

        $writer->save(base_path("../i1c_front/public/storage/xls/{$project->name}.xlsx"));

        return response()->download(base_path("../i1c_front/public/storage/xls/{$project->name}.xlsx"), "{$project->name}.xlsx");
    }

    public function exportExcelById(Request $request, $pid)
    {
        if (! $request->exists('id')) {
            abort(401, 'id not aload');
        }
        $id = $request->input('id');

        $users = User::where('id', '>', $id)
            ->wherehas('projects' , function($query) use ($pid) {
            $query->where('pid', $pid);
        })->orderBy('id', 'asc')->select('name', 'email', 'mobile', 'infomation')->get()->toArray();

        $project = Project::where('pid', $pid)->select('name','regfield')->first();
        $fields = array_column($project->regfield, 'display', 'name');
        unset($fields['photo']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($project->name);
        $row = 1;
        $sheet->fromArray(array_values(array_values($fields)), NULL, "A{$row}");

        foreach ($users as $key => $user) {
            $row++;
            $data['name'] = $user['name'];
            $data['mobile'] = $user['mobile'];
            $data['email'] = $user['email'];
            $infomation = json_decode($user['infomation'], true);
            $infomation['idc'] = implode($infomation['idc'], ':');
            $data = array_merge($fields, $data, $infomation);
            unset($data['photo']);
            $sheet->fromArray(array_values($data), NULL, "A{$row}");
        }

        $writer = new Xlsx($spreadsheet);

        $writer->save(base_path("../i1c_front/public/storage/xls/{$project->name}.xlsx"));

        return "/storage/xls/{$project->name}.xlsx";
    }
}
