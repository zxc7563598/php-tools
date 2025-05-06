# hejunjie/tools

<div align="center">
  <a href="./README.md">English</a>｜<a href="./README.zh-CN.md">简体中文</a>
  <hr width="50%"/>
</div>

🚀 `hejunjie/tools` 是一个工具整合集合包，包含多个实用组件，适用于日常 PHP 开发中的各种常见场景。

> 🧨 从 `v2.0.0` 起，本包进行了重大结构调整，拆分为多个独立包，按需组合更灵活，使用更轻量！

---

## 🧱 拆分说明

原本所有功能集中在一个仓库中，使用方便，但不利于模块化维护和功能单独复用。

因此，我把它们拆开成了以下 9 个独立的 Composer 包：

| 包名 | 简介 |
|------|------|
| [`hejunjie/utils`](https://github.com/zxc7563598/php-utils) | 一个零碎但实用的 PHP 工具函数集合库。包含文件、字符串、数组、网络请求等常用函数的工具类集合，提升开发效率，适用于日常 PHP 项目辅助功能。 |
| [`hejunjie/cache`](https://github.com/zxc7563598/php-cache) | 基于装饰器模式实现的多层缓存系统，支持内存、文件、本地与远程缓存组合，提升缓存命中率，简化缓存管理逻辑。 |
| [`hejunjie/china-division`](https://github.com/zxc7563598/php-china-division) | 定期更新，全国最新省市区划分数据，身份证号码解析地址，支持 Composer 安装与版本控制，适用于表单选项、数据校验、地址解析等场景。 |
| [`hejunjie/error-log`](https://github.com/zxc7563598/php-error-log) | 基于责任链模式的错误日志处理组件，支持多通道日志处理（如本地文件、远程 API、控制台输出），适用于复杂日志策略场景。 |
| [`hejunjie/mobile-locator`](https://github.com/zxc7563598/php-mobile-locator) | 基于国内号段规则的手机号码归属地查询库，支持运营商识别与地区定位，适用于注册验证、用户画像、数据归档等场景。 |
| [`hejunjie/address-parser`](https://github.com/zxc7563598/php-address-parser) | 收货地址智能解析工具，支持从非结构化文本中提取姓名、手机号、身份证号、省市区、详细地址等字段，适用于电商、物流、CRM 等系统。 |
| [`hejunjie/url-signer`](https://github.com/zxc7563598/php-url-signer) | 用于生成带签名和加密保护的URL链接的PHP工具包，适用于需要保护资源访问的场景. |
| [`hejunjie/google-authenticator`](https://github.com/zxc7563598/php-google-authenticator) | 一个用于生成和验证时间基础一次性密码（TOTP）的 PHP 包，支持 Google Authenticator 及类似应用。功能包括密钥生成、二维码创建和 OTP 验证。 |
| [`hejunjie/simple-rule-engine`](https://github.com/zxc7563598/php-simple-rule-engine) | 一个轻量、易用的 PHP 规则引擎，支持多条件组合、动态规则执行，适合业务规则判断、数据校验等场景。 |

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

安装后会自动引入上述全部子包，无需单独配置。

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