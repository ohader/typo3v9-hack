<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'OliverHader.DemoComments',
            'Feedback',
            'Feedback'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('demo_comments', 'Configuration/TypoScript', 'Demo Comments');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_democomments_domain_model_comment', 'EXT:demo_comments/Resources/Private/Language/locallang_csh_tx_democomments_domain_model_comment.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_democomments_domain_model_comment');

    }
);
