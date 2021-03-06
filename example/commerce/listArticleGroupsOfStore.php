<?php

/**
 * Project 'Healthy Feet' by Podolab Hoeksche Waard.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see       https://plhw.nl/
 *
 * @copyright Copyright (c) 2010 - 2019 bushbaby multimedia. (https://bushbaby.nl)
 * @author    Bas Kamer <baskamer@gmail.com>
 * @license   Proprietary License
 *
 * @package   plhw/hf-api-client
 */

declare(strict_types=1);

require_once __DIR__ . '/../setup.php';

use HF\ApiClient\Exception\GatewayException;
use HF\ApiClient\Query\Query;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

try {
    // we must get a storeId, which is different per environment.
    // as an example i'll show how you can doe a search by name
    $query = Query::create()
        ->withFilter('query', 'shop.PLHW')
        ->withPage(1, 1);

    $results = $api->commerce_listStores($query);
    $storeId = $results['data'][0]['id'] ?? '';

    $query = Query::create()
        ->withSort('ledgerNumber', true)
        ->withPage(1, 1000);

    $results = $api->commerce_listArticleGroupsOfStore($query, $storeId);
} catch (IdentityProviderException $e) {
    die($e->getMessage());
} catch (GatewayException $e) {
    \printf("%s\n\n", $e->getMessage());
    \printf('%s', $api->getLastResponseBody());
    die();
}

if ($api->isSuccess() && $results) {
    foreach ($results['data'] as $result) {
        \printf("ArticleGroup %s : %s (%s: %s)\n",
            $result['id'],
            $result['attributes']['description'],
            $result['attributes']['ledger-number'],
            $result['attributes']['code']
            );
    }
} else {
    \printf("Error (%d)\n", $api->getStatusCode());
    \print_r($results);
}
