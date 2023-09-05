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

use Assert\Assert;
use HF\ApiClient\Query\Query;

/** @var Query $query */
$storeId = $query->param('storeId');
$catalogueId = $query->param('catalogueId');
$productGroupId = $query->param('productGroupId');
$productId = $query->param('productId');

Assert::that($storeId)->uuid('storeId "%s" is not a valid UUID.');
Assert::that($catalogueId)->uuid('catalogueId "%s" is not a valid UUID.');
Assert::that($productGroupId)->uuid('productGroupId "%s" is not a valid UUID.');
Assert::that($productId)->uuid('productId "%s" is not a valid UUID.');

/* @var \HF\ApiClient\Query\Query $query */
return $query
    ->withResource(\sprintf('/commerce/stores/%s/catalogues/%s/product-groups/%s/products/%s', $storeId, $catalogueId, $productGroupId, $productId))
    ->withoutParam('storeId')
    ->withoutParam('catalogueId')
    ->withoutParam('productGroupId')
    ->withoutParam('productId');
