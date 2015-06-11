<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

include_once("../SDK/CCPRestSDK.php");

//子帐号
$subAccountSid= '';

//子帐号Token
$subAccountToken= '';

//VoIP帐号
$voIPAccount= '';

//VoIP密码
$voIPPassword= '';

//应用Id
$appId='';

//请求地址，格式如下，不需要写https://
$serverIP='sandboxapp.cloopen.com';

//请求端口 
$serverPort='8883';

//REST版本号
$softVersion='2013-12-26';

/**
 * 双向回呼
 * @param from 主叫电话号码
 * @param to 被叫电话号码
 * @param customerSerNum 被叫侧显示的客服号码  
 * @param fromSerNum 主叫侧显示的号码
 * @param promptTone 自定义回拨提示音    
 */
function callBack($from,$to,$customerSerNum,$fromSerNum,$promptTone) {
        // 初始化REST SDK
        global $appId,$subAccountSid,$subAccountToken,$voIPAccount,$voIPPassword,$serverIP,$serverPort,$softVersion;
        $rest = new REST($serverIP,$serverPort,$softVersion);
        $rest->setSubAccount($subAccountSid,$subAccountToken,$voIPAccount,$voIPPassword);
        $rest->setAppId($appId);
    
        // 调用回拨接口
        echo "Try to make a callback,called is $to <br/>";
        $result = $rest->callBack($from,$to,$customerSerNum,$fromSerNum,$promptTone);
        if($result == NULL ) {
            echo "result error!";
            break;
        }
          if($result->statusCode!=0) {
            echo "error code :" . $result->statusCode . "<br>";
            echo "error msg :" . $result->statusMsg . "<br>";
            //TODO 添加错误处理逻辑
          } else {
            echo "callback success!<br>";
            // 获取返回信息
            $callback = $result->CallBack;
            echo "callSid:".$callback->callSid."<br/>";
            echo "dateCreated:".$callback->dateCreated."<br/>";
           //TODO 添加成功处理逻辑 
          }        
}

//Demo调用     
//callBack("主叫电话号码","被叫电话号码","被叫侧显示的客服号码","主叫侧显示的号码","自定义回拨提示音");
?>
