<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <!--<link href="/dist/author.css" rel="stylesheet">-->
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">

    <style>
        * {
            padding: 0px;
            margin: 0px;
            box-sizing: border-box;
        }

        ul, ol {
            list-style: none;
        }

        html {
            width: 100%;
            height: 100%;
        }

        body {
            width: 100%;
            height: 100%;
            font-family: "PingFangSC-Regular", "Microsoft YaHei", STHeiti, Helvetica, Arial, sans-serif !important;
            font-size: 12px;
            color: #666;
            background: #FFF;
            overflow-y: hidden;
        }

        a {
            text-decoration: none;
            color: #666;
        }

        a img {
            border: none;
        }

        i, em {
            font-style: normal;
        }

        input, button, span, em, i, label {
            font-family: "amble", "Microsoft YaHei", "PingFangSC-Regular", STHeiti, Helvetica, Arial, sans-serif !important;
        }

        input[type="button"], input[type="submit"], input[type="reset"] {
            -webkit-appearance: none;
        }

        input[placeholder] {
            font-family: "amble", "Microsoft YaHei", "PingFangSC-Regular", STHeiti, Helvetica, Arial, sans-serif !important;
        }

        table {
            border-collapse: collapse;
        }

        .clearfix:after {
            content: ".";
            display: block;
            height: 0;
            clear: both;
            visibility: hidden;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
            background-color: #f0f0f0;
        }

        ::-webkit-scrollbar-track {
            background-color: #f0f0f0;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #c7c7c7;
        }

        .keyword-input-wrap {
            width: 100%;
        }

        .keyword-input-wrap .keyword-input-box {
            position: relative;
            outline: none;
            min-height: 28px;
            line-height: 28px;
            width: 100%;
            padding: 6px 0px 6px 0px;
            border-bottom: solid 1px #EFEFEF;
            cursor: text;
        }

        .keyword-input-wrap .keyword-input-box:before {
            content: '';
            height: 2px;
            display: block;
            width: 100%;
            background: #3985FF;
            position: absolute;
            left: 0px;
            bottom: -1px;
            transform: scaleX(0);
            transition: all 450ms cubic-bezier(0.23, 1, 0.32, 1) 0ms;
        }

        .keyword-input-wrap .keyword-input-box .keyword-item {
            float: left;
            max-width: 100%;
            cursor: default;
        }

        .keyword-input-wrap .keyword-input-box .keyword-item li {
            max-width: 100%;
            float: left;
        }

        .keyword-input-wrap .keyword-input-box .keyword-item .keyword {
            background: #F2F2F2;
            height: 24px;
            line-height: 24px;
            padding: 0px 0px 0px 18px;
            border-radius: 14px;
            margin-right: 12px;
            margin-top: 4px;
            margin-bottom: 2px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .keyword-input-wrap .keyword-input-box .keyword-item .keyword span {
            cursor: pointer;
        }

        .keyword-input-wrap .keyword-input-box .keyword-item .keyword em {
            width: 16px;
            display: block;
            overflow: hidden;
            height: 16px;
            border-radius: 50%;
            text-align: center;
            line-height: 16px;
            background: #A6A6A6;
            color: #FFF;
            margin: 0px 0px 0px 4px;
            opacity: 0;
            transition: all 450ms cubic-bezier(0.23, 1, 0.32, 1) 0ms;
        }

        .keyword-input-wrap .keyword-input-box .keyword-item .keyword:hover em {
            cursor: pointer;
            opacity: 1;
        }

        .keyword-input-wrap .keyword-input-box .keyword-input-area {
            outline: none;
            min-width: 50px;
            padding-right: 50px;
            max-width: 100%;
            float: left;
            min-height: 28px;
            line-height: 28px;
            font-size: 14px;
            color: #4A4A4A;
            cursor: text;
        }

        .keyword-input-wrap .keyword-input-focus:before {
            transform: scaleX(1);
        }

        .keyword-input-wrap .keyword-input-box-error:before {
            background: #f44336;
            transform: scaleX(1);
        }

        .img-upload {
            position: relative;
        }

        .img-upload .hidden-trigger {
            width: 0px;
            height: 0px;
            display: none;
        }

        .img-upload .img-upload-preview {
            cursor: pointer;
            position: relative;
            border: solid 1px #ECECEC;
            background: #F5F5F5;
            border-radius: 2px;
        }

        .img-upload .img-upload-preview .add-icon-box {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0px;
            top: 0px;
        }

        .img-upload .img-upload-preview .add-icon-box .add-icon {
            color: #C9C9C9;
            font-size: 20px;
            width: 24px;
            height: 24px;
            position: absolute;
            left: 50%;
            top: 50%;
            line-height: 24px;
            text-align: center;
            margin-left: -12px;
            margin-top: -12px;
        }

        .img-upload .img-upload-preview .img-preview-upload {
            width: 100%;
            height: 100%;
            border: solid 2px #FFF;
            position: relative;
            overflow: hidden;
            display: flex;
            position: relative;
        }

        .img-upload .img-upload-preview .img-preview-upload .img-box {
            position: absolute;
            left: 0px;
            top: 0px;
            z-index: 2;
            display: flex;
            width: 100%;
            height: 100%;
        }

        .img-upload .img-upload-preview .img-preview-upload .img-box img {
            max-width: 100%;
            max-height: 100%;
            margin: auto;
        }

        .img-upload .img-upload-preview .img-preview-upload .transparent {
            display: block;
            position: absolute;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
        }

        .img-upload .img-upload-preview .img-preview-upload:hover .inside-delbtn {
            top: 0px;
        }

        .img-upload .img-upload-preview .img-preview-upload .inside-delbtn {
            position: absolute;
            left: 0px;
            top: 0px;
            top: -100px;
            width: 100%;
            height: 100%;
            z-index: 9;
            background: rgba(0, 0, 0, 0.68);
            cursor: default;
        }

        .img-upload .img-upload-preview .img-preview-upload .inside-delbtn span {
            display: block;
            font-size: 12px;
            width: 50px;
            height: 24px;
            border-radius: 15px;
            text-align: center;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -25px;
            margin-top: -12px;
            color: #FFF;
            line-height: 24px;
            background: rgba(0, 0, 0, 0.6);
            cursor: pointer;
        }

        .img-upload .img-uploaded {
            border: solid 1px #E6E5E5;
        }

        .img-upload .file-name {
            display: block;
            text-align: center;
            overflow: hidden;
            white-space: nowrap;
            -ms-text-overflow: ellipsis;
            text-overflow: ellipsis;
            height: 24px;
            line-height: 24px;
            margin-top: 14px;
        }

        .img-upload .upload-err {
            display: block;
            text-align: left;
            height: 18px;
            line-height: 18px;
            margin-top: 18px;
            color: #EE432A;
            white-space: nowrap;
        }

        .img-upload .outside-btn {
            position: absolute;
            right: -70px;
            bottom: 8px;
            color: #999;
            font-size: 12px;
            cursor: pointer;
        }

        .img-upload .outside-btn:hover {
            color: #EE432A;
        }

        .img-upload .outside-btn:hover em svg {
            color: #EE432A !important;
        }

        .img-upload .outside-btn em {
            float: left;
            font-size: 18px;
            margin-right: 3px;
            margin-top: -1px;
        }

        .img-upload .progressbar {
            position: absolute;
            width: 80%;
            height: 10px;
            left: 50%;
            top: 50%;
            margin-left: -40%;
            margin-top: -5px;
            background: rgba(0, 0, 0, 0.4);
            z-index: 11;
            border-radius: 2px;
            overflow: hidden;
        }

        .img-upload .progressbar span {
            position: absolute;
            width: 0%;
            height: 100%;
            left: 0px;
            top: 0px;
            background: #3E89FB;
        }

        .dialog-title {
            font-size: 16px !important;
            color: #111 !important;
        }

        .dialog-overlay {
            opacity: 0 !important;
        }

        .dialog-content {
            width: 560px !important;
        }

        .cancelButton span {
            color: #e93030 !important;
        }

        .confirmButton span {
            color: #3985FF !important;
        }

        .insertImgDrawer > div:first-child {
            background: none !important;
        }

        #page {
            width: 100%;
            height: 100%;
            font-family: PingFangSC;
        }

        .page-wrap {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            min-width: 1000px;
            min-height: 600px;
            position: relative;
            background: #fafafa;
        }

        .page-wrap .main-content-wrap {
            width: 100%;
            display: flex;
            height: calc(100% - 35px);
            justify-content: center;
            align-items: center;
        }

        .page-wrap .main-content-wrap .logon-box-step3 {
            width: 780px;
            height: 460px;
            background: white;
            border-radius: 2px;
            border: 1px solid #ececec;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .logo-head {
            height: 90px;
            padding: 0 70px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ececec;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .logo-head img {
            height: 38px;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box {
            margin: 50px 0px;
            height: 240px;
            display: flex;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .main-left {
            width: 475px;
            padding: 25px 65px 0px 70px;
            border-right: 1px solid #e6e5e5;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .main-left .msg-msg {
            color: #262626;
            font-size: 20px;
            height: 30px;
            line-height: 30px;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .main-left .msg-user {
            color: #2A83FF;
            font-size: 20px;
            height: 30px;
            line-height: 30px;
            margin-top: 20px;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .main-left .msg-controls {
            margin-top: 60px;
            width: 100%;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .main-left .msg-controls .user-done {
            width: 340px;
            height: 40px;
            background: #3986ff;
            box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.15);
            border-radius: 2px;
            text-align: center;
            line-height: 40px;
            font-size: 14px;
            color: #fff;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .main-left .msg-controls .change-user {
            display: block;
            width: 56px;
            color: #3986FF;
            font-size: 14px;
            margin: 0 auto;
            margin-top: 20px;
            cursor: pointer;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .main-left .msg-controls .change-user:hover {
            text-decoration: underline;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .author-msg {
            padding-top: 20px;
            padding-left: 50px;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .author-msg .msg-desc {
            font-size: 14px;
            color: #000;
            height: 30px;
            line-height: 30px;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .author-msg .msg-desc .domain-name {
            color: #3986FF;
        }

        .page-wrap .main-content-wrap .logon-box-step3 .main-box .author-msg .authorization-item {
            color: #9B9B9B;
            height: 30px;
            line-height: 30px;
            display: flex;
            align-items: center;
        }

        .brand-description {
            width: 100%;
            height: 20px;
            text-align: center;
            margin-bottom: 12px;
            font-size: 9px;
            font-family: LucidaGrande;
            color: #d4d4d4;
        }

        /*# sourceMappingURL=author.css.map */

    </style>
</head>
<body>
<div id="page">
    <div class="page-wrap">

        <div class="main-content-wrap">
            <div class='logon-box-step3'>
                <div class="logo-head">
                    <img src='/images/logo-c.png' alt="Identity One C"/>
                </div>
                <div class="main-box">
                    <div class="main-left">
                        <div class="msg-msg">
                            检测到您已经登陆 I1c 账号：
                        </div>
                        <div class="msg-user">
                            {{ $user->name }}
                        </div>
                        <div class="msg-controls">
                            <form method="post" action="/oauth/authorize">
                                {{ csrf_field() }}

                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                <button type="submit" class="user-done">授权</button>
                            </form>
                            <form method="post" action="/oauth/authorize">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                <span class="change-user">更换账号</span>
                            </form>
                        </div>
                    </div>
                    <div class="author-msg">
                        <div class="msg-desc">
                            <span class="domain-name">{{ $client->name }}</span> 将获得以下权限：
                        </div>
                        <div class="authorization-item">
                            <svg style="width: 24px;height: 24px">
                                <path xmlns="http://www.w3.org/2000/svg"
                                      fill="#9b9b9b"
                                      d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>

                            </svg>
                            您的的 I1 所有身份信息
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="brand-description">Powered by I1c | © 2018 - 2019 Inc. All Rights Reserved.</div>
    </div>

</div>
<script>
    var API = '';
</script>
<script>
    var DEFAULT_VERSION = 8;
    var ua = navigator.userAgent.toLowerCase();
    var isIE = ua.indexOf("msie") > -1;
    var Version;
    if (isIE) {
        Version = ua.match(/msie ([\d.]+)/)[1];
        var v = parseInt(Version);
        if (v <= DEFAULT_VERSION) {
            alert('不支持当前浏览器，推荐使用Chrome浏览器打开应用');
        }
    }
</script>
<!--<script src="/dist/bundle_author.js"></script>-->

</body>
</html>