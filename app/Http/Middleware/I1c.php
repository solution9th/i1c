<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Support\Facades\Log;

class I1c
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $date       = $request->header('X-I1c-Date');
        $host       = $request->header('X-I1c-Host');
        $pid        = $request->header('X-I1c-Pid');
        $signature  = $request->header('X-I1c-Signature');
        $querybody  = $_POST;

        if (empty($pid) || empty($signature)) {
            abort(401, 'Unauthorizztion');
        }

        ksort($querybody);

        $project = Project::where('pid', $pid);

        if (! $project->exists()) {
            return response(['code' => 500, 'data' => [], 'msg' => '无效的pid']);
        }

        $secret = $project->first()->secret;

        $str2sign = $request->getMethod() . "\n"
            . $request->getPathInfo() . "\n"
            . $request->getQueryString() . "\n"
            . "X-I1c-Date={$date}&X-I1c-Host={$host}&X-I1c-Pid={$pid}\n"
            . http_build_query($querybody);

        $signatureStr = hash_hmac("sha1", $str2sign, $secret, true);
        $signatureStr = base64_encode($signatureStr);

//        Log::info("getsignature====>{$signature}\nsignature====>{$signatureStr}\nstr2sign=====>{$str2sign}");
        if ($signatureStr != $signature) {
            return response(['code' => 500, 'data' => [], 'msg' => '签名验证失败']);
        }

        return $next($request);
    }
}
