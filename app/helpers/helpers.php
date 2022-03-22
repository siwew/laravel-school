<?php

use App\helpers\ApiResponse;


/**
 * 自定义助手函数文件 通过composer自定加载引入项目
 */


if (!function_exists('api')) {
    /**
     *
     * @return ApiResponse
     */
    function api()
    {
        return app(ApiResponse::class);
    }
}


if (!function_exists('sendMail')) {

    /**
     * 发送邮件
     * @param $data     array|string    邮件内容
     * @param $cc       string          抄送人
     * @param $title    string          标题
     * @param string $view              使用模块发送邮件时候填写模版
     */
    function sendMail($title, $data, $cc = null, $view = '')
    {
        try{
            $title = config('app.env') != 'production' ? $title . '-测试环境（请忽略本邮件）' : $title;
            $to = config('mail.admin');

            if (empty($view)) {
                Mail::raw($data, function ($mail) use ($to, $title, $cc){
                    if (empty($cc)) {
                        $mail->to($to)->subject($title);
                    } else {
                        $mail->to($to)->cc($cc)->subject($title);
                    }
                });
                return;
            }
        }catch (\Exception $e){
            $data = is_array($data) ? json_encode($data, 320) : $data;
            //log
            return;
        }

    }
}