# hejunjie/tools

<div align="center">
  <a href="./README.md">English</a>｜<a href="./README.zh-CN.md">简体中文</a>
  <hr width="50%"/>
</div>

🚀 A PHP utility library that encapsulates commonly used operations for strings, arrays, files, network requests, image processing, and various helper functions (such as phone carrier lookup and ID-based address resolution) frequently encountered in daily development. More features are being continuously added...

> 🧨 Starting from `v2.0.0`, this package has undergone a major structural overhaul. It has been split into multiple standalone packages, allowing for more flexible combinations and a lighter usage experience!

---

## 🧱 Split Explanation

Originally, all features were bundled in a single repository, which made it convenient to use but not ideal for modular maintenance and independent feature reuse.

Therefore, I’ve split them into the following 9 standalone Composer packages:

| packages | describe |
|------|------|
| [`hejunjie/utils`](https://github.com/zxc7563598/php-utils) | A lightweight and practical PHP utility library that offers a collection of commonly used helper functions for files, strings, arrays, and HTTP requests—designed to streamline development and support everyday PHP projects. |
| [`hejunjie/cache`](https://github.com/zxc7563598/php-cache) | A layered caching system built with the decorator pattern. Supports combining memory, file, local, and remote caches to improve hit rates and simplify cache logic. |
| [`hejunjie/china-division`](https://github.com/zxc7563598/php-china-division) | Regularly updated dataset of China's administrative divisions with ID-card address parsing. Distributed via Composer and versioned for use in forms, validation, and address-related features |
| [`hejunjie/error-log`](https://github.com/zxc7563598/php-error-log) | An error logging component using the Chain of Responsibility pattern. Supports multiple output channels like local files, remote APIs, and console logs—ideal for flexible and scalable logging strategies. |
| [`hejunjie/mobile-locator`](https://github.com/zxc7563598/php-mobile-locator) | A mobile number lookup library based on Chinese carrier rules. Identifies carriers and regions, suitable for registration checks, user profiling, and data archiving. |
| [`hejunjie/address-parser`](https://github.com/zxc7563598/php-address-parser) | An intelligent address parser that extracts name, phone number, ID number, region, and detailed address from unstructured text—perfect for e-commerce, logistics, and CRM systems. |
| [`hejunjie/url-signer`](https://github.com/zxc7563598/php-url-signer) | A PHP library for generating URLs with encryption and signature protection—useful for secure resource access and tamper-proof links. |
| [`hejunjie/google-authenticator`](https://github.com/zxc7563598/php-google-authenticator) | A PHP library for generating and verifying Time-Based One-Time Passwords (TOTP). Compatible with Google Authenticator and similar apps, with features like secret generation, QR code creation, and OTP verification. |
| [`hejunjie/simple-rule-engine`](https://github.com/zxc7563598/php-simple-rule-engine) | A lightweight and flexible PHP rule engine supporting complex conditions and dynamic rule execution—ideal for business logic evaluation and data validation. |

---

## 💡 Why split it this way?

The main goal is to separate feature modules more clearly, while allowing users to:

- **Import only the packages they need, reducing bloat**
- **Maintain only the components they use, with clearer dependencies**
- **Provide each module with independent documentation, versioning, and update plans**
- **Reduce coupling to better support future feature evolution**

---

## 📦 Installation

If you want to `get all features at once`, simply continue using this package:

```bash
composer require hejunjie/tools
```

Installing it will automatically include all the sub-packages mentioned above, with no extra configuration needed.

Of course, you can also choose to install any individual package as needed:

```bash
composer require hejunjie/cache
composer require hejunjie/error-log
```

## 🧪 PHP Version Requirement

This suite of tools requires PHP >= 8.1.

## ❤️ About Me

This integrated package is a collection of tools I’ve refined through multiple real-world projects.

It has now been gradually split into several smaller packages, and I may continue to maintain, simplify, and optimize it over time.

You're very welcome to use it — and feel free to open an issue or submit a PR 🚀.