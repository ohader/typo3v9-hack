# Maybe get Install Tool Access

**Attacker is non-admin backend user**

`http://typo3v9-hack.ddev.local/typo3/install.php?install[controller]=maintenance&install[context]=backend`

**Attacker does not have backend user account**

Requires existing & valid `typo3conf/ENABLE_INSTALL_TOOL`

`http://typo3v9-hack.ddev.local/typo3/install.php`

## Security Vulnerability

* Media Asset handling fixed in https://review.typo3.org/#/c/59100/
  + TYPO3 affected until v7.6.31, v8.7.21, v9.5.1
* Install Tool HTTP-only cookie fixed in https://review.typo3.org/#/c/59103/
  + TYPO3 affected until v7.6.31, v8.7.21, v9.5.1