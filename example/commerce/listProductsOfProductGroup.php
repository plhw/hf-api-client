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

use HF\ApiClient\ApiClient;
use HF\ApiClient\Exception\GatewayException;
use HF\ApiClient\Query\Query;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

/** @var $api ApiClient */
require_once __DIR__ . '/../setup.php';

try {
    // we must get a storeId and a catalogueId, which is different per environment.
    // as an example i'll show how you can do a search by name

    // $query = Query::create()
    //     ->withFilter('query', 'shop.PLHW')
    //     ->withPage(1, 1);

    // $api->commerce_listStores($query);

    // // first result
    // $storeId = \reset($api->cachedResources['commerce/store'])['id'];

    // // now we search for a specific catalogue within that store
    // $query = Query::create()
    //     ->withFilter('query', 'lab.PLHW Catalogue')
    //     ->withParam('storeId', $storeId)
    //     ->withPage(1, 1);

    // $api->commerce_listCataloguesOfStore($query);

    // // first result
    // $catalogueId = \reset($api->cachedResources['commerce/catalogue'])['id'];

    // $query = Query::create()
    //     ->withFilter('code', 'S:CM:CV') // bekleding!
    //     ->withParam('storeId', $storeId)
    //     ->withParam('catalogueId', $catalogueId)
    //     ->withPage(1, 1);

    // var_dump($api->commerce_listProductGroupsOfCatalogue($query));

    // $productGroupId = \reset($api->cachedResources['commerce/product-group/product-group'])['id'];

    // // once we have the storeId and the catalogueId and productGroupId, we can get list the product groups
    // $query = Query::create()
    //     ->withParam('storeId', $storeId)
    //     ->withParam('catalogueId', $catalogueId)
    //     ->withParam('productGroupId', $productGroupId);

    $query = Query::create()
        ->withFilter('assignedValues', [
            ['attributeCode' => 'model', 'available' => true],
        ])
        ->withIncluded('assigned-values.attribute')
        ->withPage(1, 200)
        ->withParam('storeId', 'b0a8bb14-37f7-5a35-aba6-1855fc97bbe8')
        ->withParam('catalogueId', 'b0e8a8f5-c46b-5a9c-b94d-3dff7c00b9de')
        ->withParam('productGroupId', '736b926d-a541-5735-aae5-5b1802b5c297');

    $results = $api->commerce_listProductsOfProductGroup($query);

    $items = [];

    if ($api->isSuccess()) {
        foreach ($results['data'] as ['type' => $type, 'id' => $productId]) {
            $product = $api->cachedResources[$type][$productId];
            $product['attributes']['_id'] = $productId;
            $product['attributes']['_type'] = $type;
            $product['attributes']['sales-price'] = '100'; // self::getSalesPrice($product);

            $assignedValues = [];
            // loop over the assigned_values for a product (one-to-many)
            // we'll extract the type and id from data inside the loop
            foreach ($product['relationships']['assigned-values']['data'] as ['type' => $type, 'id' => $assignedValueId]) {
                // get assigned value resource
                $assignedValue = $api->cachedResources[$type][$assignedValueId];
                $assignedValue['attributes']['_id'] = $assignedValueId;
                $assignedValue['attributes']['_type'] = $type;

                // a one-2-one relationship exists between an assignedValue and an attribute resource, therefore type and id
                // extraction is a little different (not an array)
                ['type' => $type, 'id' => $attributeId] = $assignedValue['relationships']['attribute']['data'];

                // get assigned value attribute resource
                $attribute = $api->cachedResources[$type][$attributeId];
                $attribute['attributes']['_id'] = $attributeId;
                $attribute['attributes']['_type'] = $type;

                $assignedValue['attributes']['_attribute'] = $attribute['attributes'];
                $assignedValues[] = $assignedValue['attributes'];
            }

            $product = $product['attributes'];
            $product['_assigned_values'] = $assignedValues;

            $items[] = $product;
        }
    }

    \var_dump($items);
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
            "Product %s : %s (%s)\n",
            $result['id'],
            $result['attributes']['description'],
            $result['attributes']['code']
        );
    }
} else {
    \printf("Error (%d)\n", $api->getStatusCode());
    \print_r($results);
}
