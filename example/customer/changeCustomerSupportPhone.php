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
use HF\ApiClient\Exception\ClientException;
use HF\ApiClient\Exception\GatewayException;
use HF\ApiClient\Query\Query;

/** @var $api ApiClient */
require_once __DIR__ . '/../setup.php';

// required, must exist
$customerId = '81a526d5-9430-49b2-859e-af77a907fcd6'; // Behandelaar.B1 (op testing)

// possible values
$phone = null; // to unset
// or
$phone = '0' . (string) \random_int(100000000, 999999999);

$payload = [
    'supportPhoneNumber' => $phone,
];

try {
    $result = $api->customer_updateSupportPhone(
        Query::create()
            ->withParam('customerId', $customerId)
            ->withPayload($payload)
    );
} catch (ClientException $e) {
    \printf("%s\n\n", $e->getMessage());
    exit();
} catch (GatewayException $e) {
    \printf("%s\n\n", $e->getMessage());
    \printf('%s', $api->getLastResponseBody());
} catch (\Exception $e) {
    \printf("%s\n\n", $e->getMessage());
    \printf('%s', $api->getLastResponseBody());
} finally {
    if ($api->isSuccess()) {
        echo 'ok';

        // now do something with $orderLeadId.
    }
}
