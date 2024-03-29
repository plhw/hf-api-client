<?php

/**
 * Project 'Healthy Feet' by Podolab Hoeksche Waard.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://plhw.nl/
 *
 * @copyright Copyright (c) 2010 bushbaby multimedia. (https://bushbaby.nl)
 * @author Bas Kamer <baskamer@gmail.com>
 * @license Proprietary License
 *
 * @package plhw/hf-api-client
 */

declare(strict_types=1);

/** @var $api ApiClient */
require_once __DIR__ . '/../setup.php';

use HF\ApiClient\ApiClient;
use HF\ApiClient\Exception\GatewayException;
use HF\ApiClient\Query\Query;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

try {
    // we must get a storeId and a catalogueId, which is different per environment.
    // as an example i'll show how you can do a search by name

    $query = Query::create()
        ->withFilter('query', 'shop.PLHW')
        ->withPage(1, 1);

    $api->commerce_listStores($query);

    // first result
    $storeId = \reset($api->cachedResources['commerce/store'])['id'];

    // now we search for a specific catalogue within that store
    $query = Query::create()
        ->withFilter('query', 'lab.PLHW Catalogue')
        ->withParam('storeId', $storeId)
        ->withPage(1, 1);

    $api->commerce_listCataloguesOfStore($query);

    // first result
    $catalogueId = \reset($api->cachedResources['commerce/catalogue'])['id'];

    $query = Query::create()
        ->withParam('storeId', $storeId)
        ->withParam('catalogueId', $catalogueId)
        ->withSort('code', true)
        ->withPage(1, 1000);

    $api->commerce_listProductGroupsOfCatalogue($query);

    $results = $api->commerce_listProductGroupsOfCatalogue($query, $storeId, $catalogueId);
} catch (IdentityProviderException $e) {
    exit($e->getMessage());
} catch (GatewayException $e) {
    \printf("%s\n\n", $e->getMessage());
    \printf('%s', $api->getLastResponseBody());
    exit();
}

if ($api->isSuccess() && $results) {
    foreach ($results['data'] as $result) {
        \printf(
            "ProductGroup %s : %s (%s)\n",
            $result['id'],
            $result['attributes']['description'],
            $result['attributes']['code']
        );
    }
} else {
    \printf("Error (%d)\n", $api->getStatusCode());
    \print_r($results);
}
