<?php

/** @var \HF\ApiClient\Query\Query $query */
$query = $params[0] ?? \HF\ApiClient\Query\Query::create();
$storeId = $params[1] ?? null;

if(! $storeId) {
    throw new \Exception('You must provide a storeId');
}

return [
    'url'      => sprintf( '/commerce/stores/%s/retrieve-sandalinos-composition%s', $storeId, $query),
    'method'   => 'GET',
    'header'   => $query->headers(),
    'response' => [
        'format' => 'json',
        'valid_codes' => ['200'],
    ],
];