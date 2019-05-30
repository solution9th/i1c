<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>

        .msg-box{
            /*width: 100%;*/
            padding: 50px;
            margin: 50px;
            border: 1px solid #9B9B9B;
        }
        .msg-msg{
            color: #9B9B9B;
            margin-bottom: 20px;
            padding-bottom: 10px;
            margin-top: 20px;
            font-size: 14px;
        }
        .msg-head{
            border-bottom: 1px solid #9B9B9B;
            width: 70%;

        }
        .msg-code{
            color: #000;
            font-size: 22px;
            padding-bottom: 20px;

        }
    </style>
</head>
<body>

<div class="msg-box">
    <div class="msg-msg">
        尊敬的 {{ $project }} 用户：

    </div>

    <div class="msg-msg">
        我们收到了一项请求，要求通过您的电子邮箱地址访问您的{{ $project }} 账号{{ $email }} ,您的验证码为：

    </div>
    <div class="msg-msg msg-code">
        {{ $captcha }}
    </div>
    <div class="msg-msg">
        验证码会在5分钟后过期，

    </div>

    <div class="msg-msg">
        如果您并未请求此验证码，则可能是他人正在尝试访问以下账号 {{ $email }} ,请勿将此验证码发给或提供给任何人

    </div>
    <div class="msg-msg">
        此致，

    </div>
    <div class="msg-msg">
        {{ $project }} 团队敬上

    </div>
</div>

</body>
</html>