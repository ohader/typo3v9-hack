# TYPO3 v9 Hacking

This project is using TYPO3 v9.5.3 with a couple of unfixed/reverted security fixes.
It is supposed to be **insecure** and serves as foundation for showing potential attack
vectors against TYPO3.

## H4CK5

* Install Tool access via Cross-Site Scripting & Cookie not using HTTP-only flag
  + [1-XSS-MaybeGetInstallToolAccess.md](.hack/1-XSS-MaybeGetInstallToolAccess.md)
* Remote controlling clients via Cross-Site Scripting & BeEF
  + [2-XSS-JustBeEF.md](.hack/2-XSS-JustBeEF.md)
* Retrieve `encryptionKey` in order to create signatures ("signed attacks")
  & create new admin user via Insecure Deserialization and injected TypoScript
  + [3-API-RetrieveLocalConfiguration.md](.hack/3-API-RetrieveLocalConfiguration.md)
  + [4-DESER-CreateAdminAccount.md](.hack/4-DESER-CreateAdminAccount.md)
* Classic SQL Injection via Stupid TypoScript
  + [5-SQLI-XSS-StupidTypoScript.md](.hack/5-SQLI-XSS-StupidTypoScript.md)

## Video

The mentioned hacks were demonstrated during TYPO3camp Mitteldeutschland, Dresden, DE
in January 2019 - spoken language is unfortunately German, Slides are in English.

https://www.youtube.com/watch?v=lefgKJSWqx0&feature=youtu.be&t=10800

## Installation

* having Docker and DDEV installed
  + https://ddev.readthedocs.io/en/stable/#docker-installation
  + https://ddev.readthedocs.io/en/stable/#installation-or-upgrade-windows
* clone git repository
* execute ddev start
* execute ddev composer install
* import database using ddev import-db --src ./ddev/database.sql.gz
* web project is provided at http://typo3v9-hack.ddev.site/


## TYPO3 Backend Accounts

* admin/password
* user/password

## Browser Exploitation Framework (BeEF)

**Hook to be used as XSS attack vector (to be injected in target application):**

http://typo3v9-hack.ddev.site:3000/hook.js

**Admin and Control interface:**

http://typo3v9-hack.ddev.site:3000/ui/panel

* username: admin
* password: joh316

## Links

* https://beefproject.com/
* https://typo3.org/help/security-advisories/
* https://typo3.org/cms/roadmap/maintenance-releases/

## Security Contact

In case of finding additional security issues in the TYPO3 project please get in
touch with the TYPO3 Security Team at [security@typo3.org](mailto:security@typo3.org).
Please do not disclose issues in the public without according coordination.
