# Easy SMS 短信插件

目前支持阿里云、腾讯云、火山引擎三家服务商的短信服务。

## 插件安装

- 使用标识名安装: `EasySms`
- 使用指令安装: `php artisan market:require EasySms`

## 配置示例

| 配置项 | 腾讯云 | 阿里云 |
| --- | --- | --- |
| Key ID | 访问管理->用户->用户列表->用户详情->API 密钥<br>`SecretId` | RAM 访问控制->身份管理->用户<br>`AccessKey ID` |
| Key Secret | 访问管理->用户->用户列表->用户详情->API 密钥<br>`SecretKey` | RAM 访问控制->身份管理->用户<br>`AccessKey Secret` |
| SDK App ID | 短信->应用管理->应用列表->SDK AppID | 留空 |
| 匹配验证码模板 | 未使用国际短信，保持默认即可<br>`{"86":"zh-Hans","other":"en"}` | 未使用国际短信，保持默认即可<br>`{"86":"zh-Hans","other":"en"}` |

**权限说明：**
- 腾讯云：权限->权限策略->短信服务（SMS）全读写访问权限 `QcloudSMSFullAccess`
- 阿里云：权限管理->系统策略->管理短信服务(SMS)的权限 `AliyunDysmsFullAccess`

**短信模板示例：**

| 配置项 | 腾讯云示例 | 阿里云示例 |
| --- | --- | --- |
| 短信签名名称 | `Fresns` | `Fresns` |
| 模板参数 | `1107229` | `SMS_115200038` |
| 验证码变量名 | `{1}` | `${code}` |


## 开发说明

### 配置项键名

- 服务商类型 `easysms_type`
    - 1.阿里云
    - 2.腾讯云
- Key ID `easysms_keyid`
- Key Secret `easysms_keysecret`
- SDK App ID `easysms_sdk_appid`
    - 腾讯云 SmsSdkAppId
    - 阿里云留空
- 国际区号匹配语言标签 `easysms_linked`

```json
// easysms_linked
// 根据传参国际区号去匹配模板语言标签，如果区号查询不到匹配，就使用 other 匹配的语言标签模板
{
    "国际区号": "验证码模板语言标签",
    "other": "其他区号使用该模板"
}

// 示例
{
    "86": "zh-Hans",
    "other": "en"
}
```

### 功能介绍

- 验证码短信模板配置 [https://docs.fresns.com/zh-hans/open-source/configs/panel/send.html#%E9%AA%8C%E8%AF%81%E7%A0%81%E6%A8%A1%E6%9D%BF%E8%AE%BE%E7%BD%AE](https://docs.fresns.com/zh-hans/open-source/configs/panel/send.html#%E9%AA%8C%E8%AF%81%E7%A0%81%E6%A8%A1%E6%9D%BF%E8%AE%BE%E7%BD%AE)
- 根据国际区号判断是国内短信还是国际。
- 支持 `sendCode` 命令字
    - 1、接收到命令字请求后，判断配置表 `easysms_type` 类型，决定发哪家短信；
    - 2、根据区号判断是发国内短信还是国际短信（根据区号匹配模板语言标签）；
    - 3、根据命令字传参 templateId 和 langTag 两个参数（找不到 langTag 匹配的模板，则使用默认语言），去匹配需要发信的模板；
    - 4、生成验证码（验证码有效期设定为 10 分钟），验证码位于短信中 templateParam 变量名；
    - 5、使用模板配置和替换模板中变量，请求云服务商发送短信。
- 支持 `sendSms` 命令字，直接传参短信配置，插件解析配置直接请求发送短信。
    - 1、接收到命令字请求后，判断配置表 `easysms_type` 类型，决定发哪家短信；
    - 2、根据区号判断是发国内短信还是国际短信；
    - 3、根据命令字传参 templateCode 去发送模板短信；
    - 4、如果 templateParam 参数有值，直接传给服务商；
    - 具体「自定义发信」示例见下方表格。

| 命令字参数 | 腾讯云参数 | 腾讯云参数示例 | 阿里云参数 | 阿里云参数示例 |
| --- | --- | --- | --- | --- |
| countryCallingCode |  | 86 |  | 86 |
| purePhone | PhoneNumberSet | 13900120012 | PhoneNumbers | 13900120012 |
| signName | SignName | Fresns | SignName | Fresns |
| templateCode | TemplateId | 1145184 | TemplateCode | SMS_225391766 |
| templateParam | templateParamSet1 | "123456","5分钟" | TemplateParam | {"password":"1234567890","time":"5分钟"} |
