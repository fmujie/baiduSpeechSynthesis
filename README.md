现仅有百度AI平台的语音合成功能

## 安装

 1. 安装包文件

	``` bash
	$ composer require fmujie/baidu-speech-synthesis
	```

## 配置

1. 注册 `ServiceProvider`:
	
	```php
	Fmujie\BaiduSpeech\BaiduSpeechServiceProvider::class,
	```

2. 创建配置文件：

	```shell
	php artisan vendor:publish
	```
	
	通常得需要选择`publish`哪一个服务，因为没带参数，选择编号 **[n ]**
	
	~~~bash
	[n ] Provider: Fmujie\BaiduSpeech\BaiduSpeechServiceProvider
	~~~
	
	执行命令后会在 `config` 目录下生成本扩展配置文件：`laravel-baidu-speech.php`。
	
3. 在 `.env` 文件中增加如下配置：

	- `BAIDU_APP_ID`：百度`AppId`。

	- `BAIDU_API_KEY`：百度`ApiKey`。

	- `BAIDU_SECRET_KEY`：百度`SecretKey`。

## 使用

1. 语音合成
  
    ```php
    Fmujie\BaiduSpeech\BaiduSpeech::combine($text, $userID, $lan, $speed, $pitch, $volume, $person);
    ```
    
    接口字段：
    
    | 参数  | 类型  | 说明  | 可为空  |
    | ------------ | ------------ | ------------ | ------------ |
    | text | String | 合成的文本 | N |
    | userID | String | 用户唯一标识 | Y |
    | lan | String | 语言，可选值 ['zh']，默认为zh | Y |
    | speed | Integer | 语速，取值0-9，默认为5中语速 | Y |
    | pitch | Integer | 音调，取值0-9，默认为5中语调 | Y |
    | volume | Integer | 音量，取值0-15，默认为5中音量 | Y |
    | person | Integer | 发音人选择, 0为女声，1为男声，3为情感合成-度逍遥，4为情感合成-度丫丫，默认为普通女 | Y |
    
    接口返回字段详细见 [百度官方文档](https://cloud.baidu.com/doc/SPEECH/TTS-Online-PHP-SDK.html).
    
    返回的语音信息保存在storage/audios/back.mp3中
    
    #### 调用示例
    
    ~~~php
    <?php
    namespace App\Http\Controllers\test;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Fmujie\BaiduSpeech\BaiduSpeech;
    
    class TestController extends Controller
    {
        public function test()
        {
            $text = '我真的很爱很爱你';
            $userID = 'fmujie';
            $lan = 'zh';
            $speed = 5;
            $pitch = 5;
            $volume = 5;
            $person = 4;
            $data = BaiduSpeech::combine($text, $userID, $lan, $speed, $pitch, $volume, $person);
            dd($data);
            return "";
        }
    }
    ~~~
    
    

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
