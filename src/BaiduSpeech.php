<?php

namespace Fmujie\BaiduSpeech;

use Illuminate\Support\Facades\Storage;
use Fmujie\BaiduSpeech\Libs\AipSpeech;

/**
 * 百度语音SDK库
 * @package Fmujie\BaiduSpeech
 */
class BaiduSpeech{

    protected $statusCode = 200;
    protected $returned = [
        'result' => [
            'code' => 0,
            'status' => 'error',
            'msg' => null
            ]
    ];
    protected $disk = 'public';
    /**
     * @return array
     */
    public static function loadConfig()
    {
        $config = [
            'appID'  => config('laravel-baidu-speech.app_id'),
            'apiKey' => config('laravel-baidu-speech.api_key'),
            'secretKey' => config('laravel-baidu-speech.secret_key'),
        ];
        return $config;
    }

    /**
     * 语音合成
     *
     * @param $text string 合成的文本
     * @param $userID string 用户唯一标识
     * @param $lan string 语音 ['zh']
     * @param $speed integer 语速，取值0-9，默认为5中语速
     * @param $pitch integer 音调，取值0-9，默认为5中语调
     * @param $volume integer 音量，取值0-15，默认为5中音量
     * @param $person integer 发音人选择, 0为女声，1为男声，3为情感合成-度逍遥，4为情感合成-度丫丫，默认为普通女
     * @param $fileName string 存储文件路径名称
     * @return array
     */
    public function combine($text, $userID=null, $lan='zh', $speed=5, $pitch=5, $volume=5, $person=4, $fileName=null, $disk = 'public')
    {
        if(!$text){
            $return['msg'] = '缺少合成的文本';
            return $return;
        }
        if($speed<0 || $speed>9){
            $return['msg'] = '语速错误';
            return $return;
        }
        if($pitch<0 || $pitch>9){
            $return['msg'] = '音调错误';
            return $return;
        }
        if($volume<0 || $volume>15){
            $return['msg'] = '音量错误';
            return $return;
        }
        if($person<0 || $person>4){
            $return['msg'] = '发音人错误';
            return $return;
        }
        $config = self::loadConfig();
        $aipSpeech = new AipSpeech($config['appID'], $config['apiKey'], $config['secretKey']);
        $options = [
            'lan' => $lan,
            'spd' => $speed,
            'pit' => $pitch,
            'vol' => $volume,
            'per' => $person
        ];
        if(!$userID){
            $options['cuid'] = $userID;
        }
        $response = $aipSpeech->synthesis($text, $lan, 1, $options);
        if(!is_array($response)){
            $fileName = date('Y_m_d').'/' . 'audio_file/' . md5(time()) .mt_rand(0,9999).'.mp3';
            Storage::disk($disk)->put($fileName, $response) ? $filePath = Storage::url($fileName) : $filePath = null;
            if (!$filePath) {
                $this->returned['result']['msg'] = '文件储存失败';
                return $this->returned;
            }
            Storage::put($filePath, $response);
            $this->success('语音合成成功');
            $this->returned['result']['file_path'] = $filePath;
        }else{
            $this->returned['result']['msg'] = '语音合成错误,错误码:'.$response['error_code'].',错误信息:'.$response['error_msg'];
        }
        return $this->returned;
    }

    protected function success($successMsg, $statusCode = 200)
    {
        $this->returned['result']['code'] = 1;
        $this->returned['result']['status'] = 'success';
        $this->returned['result']['msg'] = $successMsg;
        $this->statusCode = $statusCode;
    }

    protected function respond()
    {
        return response()->json($this->returned, $this->statusCode);
    }
}