
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

|方法|说明|
|:----|:----|
| Str::containsAny() | 检查字符串中是否存在数组中的内容 |
| Str::generateRandomString() | 生成随机字符串 |
| Str::getRandomSurname() | 获取随机姓氏 |
| Str::truncateString() | 截断字符串 |
| Str::maskString() | 字符串掩码 |
| Str::removeWhitespace() | 移除字符串中的所有空白字符 |
| Str::stringEncrypt() | 字符串加密 |
| Str::stringDecrypt() | 字符串解密 |
| Str::convertSecondsToDuration() | 根据秒数转换为可读性时间 |

### 数组操作

|方法|说明|
|:----|:----|
| Arr::arrayIntersect() | 获取两个数组的交集 |
| Arr::sortByField() | 根据二维数组中的指定字段排序 |
| Arr::removeDuplicatesByField() | 根据二维数组中指定字段去重 |
| Arr::groupByField() | 根据二维数组中的指定字段进行分组 |
| Arr::arrayToCsv() | 数组转换为 CSV 格式的字符串 |


### 文件操作

|方法|说明|
|:----|:----|
| FileUtils::readFile() | 读取文件内容 |
| FileUtils::writeToFile() | 将内容写入文件 |
| FileUtils::getFileExtension() | 获取文件扩展名 |
| FileUtils::joinPaths() | 拼接多个路径 |
| FileUtils::getFileNameWithoutExtension() | 获取文件名（不带扩展名） |


### 网络请求操作

|方法|说明|
|:----|:----|
| HttpClient::sendGetRequest() | 使用 cURL 发送 GET 请求 |
| HttpClient::sendPostRequest() | 使用 cURL 发送 POST 请求 |


### 图片操作

|方法|说明|
|:----|:----|
| Img::downloadImageFromUrl() | 从 URL 下载图片 |
| Img::imageToBase64() | 将图片转换为 Base64 字符串 |
| Img::base64ToImage() | 将 Base64 字符串保存为图片 |
| Img::compressImage() | 压缩图片到指定大小（单位 KB），支持多种格式转换为 JPEG |
| Img::resizeImage() | 调整图片分辨率，保持宽高比 |


### 手机号运营商查询

|方法|说明|
|:----|:----|
| PhoneCarrierLookup::getCarrierInfo() | 根据手机号获取运营商信息 |


### 身份证号户籍地查询

|方法|说明|
|:----|:----|
| IdAddressLookup::getAddressInfo() | 根据身份证号获取地址信息 |

---

该库后续将持续更新，添加更多实用功能。欢迎大家提供建议和反馈，我会根据大家的意见实现新的功能，共同提升开发效率。