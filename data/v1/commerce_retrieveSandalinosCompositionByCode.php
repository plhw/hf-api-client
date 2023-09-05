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

/* @var Query $query */

use Assert\Assert;
use HF\ApiClient\Query\Query;

$storeId = $query->param('storeId');
$code = $query->param('code');

Assert::that($code)->notEmpty('Enter a code as argument')->regex('/^[SANDLIO]{8}$/', 'That code is not in valid format');

Assert::that($storeId)->uuid('storeId "%s" is not a valid UUID.');

/* @var \HF\ApiClient\Query\Query $query */
return $query
    ->withResource(\sprintf('/commerce/stores/%s/retrieve-sandalinos-composition', $storeId))
    ->withoutParam('storeId');
