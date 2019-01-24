<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'OliverHader.DemoComments',
            'Feedback',
            [
                'Comment' => 'list, create'
            ],
            // non-cacheable actions
            [
                'Comment' => 'list, create'
            ]
        );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    feedback {
                        iconIdentifier = demo_comments-plugin-feedback
                        title = LLL:EXT:demo_comments/Resources/Private/Language/locallang_db.xlf:tx_demo_comments_feedback.name
                        description = LLL:EXT:demo_comments/Resources/Private/Language/locallang_db.xlf:tx_demo_comments_feedback.description
                        tt_content_defValues {
                            CType = list
                            list_type = democomments_feedback
                        }
                    }
                }
                show = *
            }
       }'
    );
		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
		
			$iconRegistry->registerIcon(
				'demo_comments-plugin-feedback',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:demo_comments/Resources/Public/Icons/user_plugin_feedback.svg']
			);
		
    }
);
