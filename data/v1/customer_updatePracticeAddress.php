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

/* @var \HF\ApiClient\Query\Query $query */

use Assert\Assert;

$customerId = $query->param('customerId');
$practiceId = $query->param('practiceId');
$payload = $query->payload();

Assert::that($customerId)->uuid('customerId "%s" is not a valid UUID.');
Assert::that($practiceId)->uuid('practiceId "%s" is not a valid UUID.');
Assert::that($payload)->notNull('payload is null')->isArray('payload must be array')->notEmpty('payload is empty');

$payload['customerId'] = $customerId;
$payload['practiceId'] = $practiceId;

Assert::that($payload)
    ->keyExists('customerId')
    ->keyExists('practiceId')
    ->keyExists('label')
    ->keyExists('address');

if (\in_array($payload['label'], ['business', 'billing'])) {
    Assert::that($payload['address'])->notNull(\sprintf('address for \'%s\' may not be set to null', $payload['label']));
}

return $query
    ->withPayload($payload)
    ->withoutParam('customerId')
    ->withoutParam('practiceId')
    ->withMethod('POST')
    ->withResource(\sprintf('/customer/practice/change-address'));
