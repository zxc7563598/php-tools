# hejunjie/tools

🚀 `hejunjie/tools` 是一个工具整合集合包，包含多个实用组件，适用于日常 PHP 开发中的各种常见场景。

> 🧨 从 `v2.0.0` 起，本包进行了重大结构调整，拆分为多个独立包，按需组合更灵活，使用更轻量！

---

## 🧱 拆分说明

原本所有功能集中在一个仓库中，使用方便，但不利于模块化维护和功能单独复用。

因此，我把它们拆开成了以下 9 个独立的 Composer 包：

| 包名 | 简介 |
|------|------|
| [`hejunjie/cache`](https://github.com/zxc7563598/php-cache) | 装饰器模式多层缓存系统，支持内存/Redis/文件组合 |
| [`hejunjie/china-division`](https://github.com/zxc7563598/php-china-division) | 全国省市区划分数据，适合地址选择器、地区映射、身份证查询归属地等 |
| [`hejunjie/error-log`](https://github.com/zxc7563598/php-error-log) | 责任链模式日志处理系统，支持文件、控制台、远程日志 |
| [`hejunjie/mobile-locator`](https://github.com/zxc7563598/php-mobile-locator) | 基于国内号段规则的手机号归属地查询，含运营商识别 |
| [`hejunjie/utils`](https://github.com/zxc7563598/php-utils) | 常用数组、字符串、文件、网络等工具函数集合 |
| [`hejunjie/address-parser`](https://github.com/zxc7563598/php-address-parser) | 收货地址智能解析工具，支持从非结构化文本中提取用户/地址信息 |
| [`hejunjie/url-signer`](https://github.com/zxc7563598/php-url-signer) | URL 签名工具，支持对 URL 进行签名和验证。 |
| [`hejunjie/google-authenticator`](https://github.com/zxc7563598/php-google-authenticator) | Google Authenticator 及类似应用的密钥生成、二维码创建和 OTP 验证。 |
| [`hejunjie/simple-rule-engine`](https://github.com/zxc7563598/php-simple-rule-engine) | 一个轻量、易用的 PHP 规则引擎，支持多条件组合、动态规则执行。 |

---

## 💡 为何这样拆？

主要是为了更清晰地分离功能模块，同时让使用者能：

- **按需引入所需包，减少冗余**
- **只维护自己用到的组件，依赖更清晰**
- **每个模块都有独立文档、版本、更新计划**
- **降低耦合度，适应未来功能演进**

---

## 📦 安装方式

如果你希望 **一次性获得全部功能**，继续使用本包即可：

```bash
composer require hejunjie/tools
```

安装后会自动引入上述 5 个子包，无需单独配置。

当然，你也可以按需引入任意一个包：

```bash
composer require hejunjie/cache
composer require hejunjie/error-log
```

## 🧪 PHP 版本要求
本系列工具包统一要求 PHP >= 8.0

## ❤️ 关于我
这个整合包是我在多个实际项目中反复提炼出来的工具集合。

目前已经逐步拆分为多个子包，后续也可能继续维护、精简、优化。

欢迎使用，也欢迎提 Issue 或 PR 🚀