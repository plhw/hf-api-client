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

namespace HF\ApiClient;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseHandler
{
    public function handle(Response $response)
    {
        $contents = $response->getBody()->getContents();

        return \json_decode($contents, true);
    }
}
