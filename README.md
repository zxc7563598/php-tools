
## 发行说明

一个简单的 PHP 工具库，很多代码的实现原理并不复杂，但因经常在不同的项目中遇到，反复书写让人很苦恼，索性将一些在工作中会用到的一些方法汇总在一起


## 安装指南

使用 Composer 安装：

```shell
composer require hejunjie/tools
```

使用示例

```php
use Hejunjie\Tools\Str;

// 示例：获取随机姓氏
$result = Str::getRandomSurname();
```

## 当前支持的方法列表

### 字符串操作

| 方法                            | 说明                             |
| :------------------------------ | :------------------------------- |
| Str::containsAny()              | 检查字符串中是否存在数组中的内容 |
| Str::padString()                | 补充特定字符串，使其达到指定长度 |
| Str::replaceFirst()             | 替换字符串中第一次出现的搜索值   |
| Str::generateRandomString()     | 生成随机字符串                   |
| Str::getRandomSurname()         | 获取随机姓氏                     |
| Str::truncateString()           | 截断字符串                       |
| Str::maskString()               | 字符串掩码                       |
| Str::removeWhitespace()         | 移除字符串中的所有空白字符       |
| Str::stringEncrypt()            | 字符串加密                       |
| Str::stringDecrypt()            | 字符串解密                       |
| Str::convertSecondsToDuration() | 根据秒数转换为可读性时间         |

### 数组操作

| 方法                           | 说明                             |
| :----------------------------- | :------------------------------- |
| Arr::arrayIntersect()          | 获取两个数组的交集               |
| Arr::sortByField()             | 根据二维数组中的指定字段排序     |
| Arr::removeDuplicatesByField() | 根据二维数组中指定字段去重       |
| Arr::groupByField()            | 根据二维数组中的指定字段进行分组 |
| Arr::arrayToCsv()              | 数组转换为 CSV 格式的字符串      |
| Arr::xmlParse()                | xml解析为数组                    |
| Arr::arrayToXml()              | 数组转换为xml                    |


### 文件操作

| 方法                                     | 说明                       |
| :--------------------------------------- | :------------------------- |
| FileUtils::readFile()                    | 读取文件内容               |
| FileUtils::writeToFile()                 | 将内容写入文件             |
| FileUtils::getFileExtension()            | 获取文件扩展名             |
| FileUtils::joinPaths()                   | 拼接多个路径               |
| FileUtils::getFileNameWithoutExtension() | 获取文件名（不带扩展名）   |
| FileUtils::fileDelete()                  | 删除文件或目录             |
| FileUtils::writeUniqueLinesToFile()      | 获取文件中的唯一行（去重） |
| FileUtils::getCommonLinesFromFiles()     | 从多个文件中获取交集行     |
| FileUtils::extractColumnFromCsvFiles()   | 从多个csv文件中快速提取列  |


### 网络请求操作

| 方法                          | 说明                     |
| :---------------------------- | :----------------------- |
| HttpClient::sendGetRequest()  | 使用 cURL 发送 GET 请求  |
| HttpClient::sendPostRequest() | 使用 cURL 发送 POST 请求 |


### 图片操作

| 方法                        | 说明                                                   |
| :-------------------------- | :----------------------------------------------------- |
| Img::downloadImageFromUrl() | 从 URL 下载图片                                        |
| Img::imageToBase64()        | 将图片转换为 Base64 字符串                             |
| Img::base64ToImage()        | 将 Base64 字符串保存为图片                             |
| Img::compressImage()        | 压缩图片到指定大小（单位 KB），支持多种格式转换为 JPEG |
| Img::resizeImage()          | 调整图片分辨率，保持宽高比                             |


### 手机号运营商查询

| 方法                                 | 说明                     |
| :----------------------------------- | :----------------------- |
| PhoneCarrierLookup::getCarrierInfo() | 根据手机号获取运营商信息 |


### 身份证号户籍地查询

| 方法                              | 说明                     |
| :-------------------------------- | :----------------------- |
| IdAddressLookup::getAddressInfo() | 根据身份证号获取地址信息 |

### 多层缓存

**注意**：为了拓展方便，代码仅仅实现了缓存层（内存/redis/文件），实际应用场景中建议自行完善数据层，大概代码如下所示

```php
<?php

// 自定义数据源 - 数据库层
class UserDataSource implements \Hejunjie\Tools\Cache\Interfaces\DataSourceInterface
{
    protected DataSourceInterface $wrapped;
    
    // 构造函数，如果是最后一层则不需要构造函数
    // public function __construct(
    //     DataSourceInterface $wrapped
    // ) {
    //     $this->wrapped = $wrapped;
    // }

    public function get(string $key): ?string
    {
        // 根据 key 在数据库中获取对应内容
        // 返回内容字符串 `string`

        // 如果下一层返回数据，则在当前层存储。如果是最后一层则不需要下列代码
        // $content = $this->wrapped->get($key);
        // if ($content !== null) {
        //     $this->set($key, $content);
        // }
        // return $content;

    }

    public function set(string $key, string $value): bool
    {
        // 根据 key 在数据库中存储 value
        // 返回存储结果 `bool`
    }
}

```

实际使用方法：

```php
<?php

use Hejunjie\Tools\Cache\Decorators;

// 构建缓存链：内存 → Redis → 文件 → 自定义数据源
$cache = new Decorators\MemoryCache(
    new Decorators\RedisCache(
        new Decorators\FileCache(
            new UserDataSource(
                ... // 可以继续套娃
            ),
            '[文件]缓存文件夹路径',
            '[文件]缓存时长(秒)'
        ),
        '[redis]配置'
        '[redis]前缀'
        '[redis]是否持久化链接'
    ),
    '[内存]缓存时长(秒)',
    '[内存]缓存数量(防止内存溢出)'
);

// 获取数据
$data = $cache->get('key')
// 存储数据
$data = $cache->set('key','value')

```

| 存储层                 | 数据保留时间                     |
| :--------------------- | :------------------------------- |
| [内存] MemoryCache      | 进程生命周期（脚本结束即消失）   |
| [redis] RedisCache      | 根据配置的TTL（默认1小时）       |
| [文件] FileCache        | 文件系统保留，直到过期或手动删除 |
| [数据库] UserDataSource | 用户自行实现                     |

#### Redis 配置
- cluster: 是否集群 `bool` `默认 false`
- hosts: 集群主机 `array` `cluster 为 true 必填`
- host: 主机 `string` `cluster 为 false 必填`
- port: 端口 `int` `默认 6379`
- password: 密码 `string` `默认 null`
- db: 数据库 `int` `非必须，不传则等于根据 redis 配置选择`
- ttl: 过期时间 `int` `默认 3600`
- timeout: 超时时间 `float` `默认 2.0`
- read_timeout: 读取超时时间 `float` `默认 2.0`


### 日志记录

不同框架通常自带日志系统，但要么强绑定到框架，更换框架就要重构日志方案，要么像 Monolog 这类强大的日志系统功能过于庞大。而为了在不同框架中保持通用性，同时避免过度复杂，我基于责任链模式实现了一个轻量级的日志模块

使用方法：

```php
<?php

$log = new \Hejunjie\Tools\Log\Logger([
    new \Hejunjie\Tools\Log\Handlers\ConsoleHandler(),                // 打印到控制台
    new \Hejunjie\Tools\Log\Handlers\FileHandler('日志存储文件夹路径'),  // 存储到文件
    new \Hejunjie\Tools\Log\Handlers\RemoteApiHandler('请求url')       // 发送到某个地址
]);

$log->info('标题','内容',['上下文']);     // INFO 级
$log->warning('标题','内容',['上下文']);  // WARNING 级
$log->error('标题','内容',['上下文']);    // ERROR 级

$log->log('自定义级别','标题','内容',['上下文']);

```

**以及**：如果有其他的拓展需要可以自行实现，实现 ` \Hejunjie\Tools\Log\LogHandlerInterface ` 即可



---

该库后续将持续更新，添加更多实用功能。欢迎大家提供建议和反馈，我会根据大家的意见实现新的功能，共同提升开发效率。