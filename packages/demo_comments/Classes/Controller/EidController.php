<?php
namespace OliverHader\DemoComments\Controller;

/***
 *
 * This file is part of the "Demo Comments" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Oliver Hader <oliver.hader@typo3.org>
 *
 ***/

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * EidController
 */
class EidController
{
    private const TABLE_NAME = 'tx_democomments_domain_model_comment';

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $search = $request->getParsedBody()['search'] ?? $request->getQueryParams()['search'] ?? null;
        $debug = $request->getParsedBody()['debug'] ?? $request->getQueryParams()['debug'] ?? false;

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::TABLE_NAME);
        $statement = $queryBuilder
            ->select('uid', 'date', 'email', 'message')
            ->from(self::TABLE_NAME);

        if ($search !== null) {
            $escapedSearch = $this->escape($queryBuilder->getConnection(), '%' . $search . '%');
            $queryBuilder->orWhere(
                $queryBuilder->expr()->like('message', $escapedSearch),
                $queryBuilder->expr()->like('email', $escapedSearch)
            );
        }

        $data = ['items' => $statement->execute()->fetchAll()];
        if ($debug) {
            $data['query'] = $statement->getSQL();
        }
        return new JsonResponse($data);
    }

    private function escape(Connection $connection, string $string): string
    {
        $literal = $connection->getDatabasePlatform()->getStringLiteralQuoteCharacter();
        return sprintf('%s%s%s', $literal, $string, $literal);
    }
}
