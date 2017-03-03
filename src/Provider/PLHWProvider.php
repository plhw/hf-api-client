<?php

declare(strict_types=1);

/**
 * Project 'Healthy Feet' by Podolab Hoeksche Waard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link      https://plhw.nl/
 * @copyright Copyright (c) 2010 - 2017 bushbaby multimedia. (https://bushbaby.nl)
 * @author    Bas Kamer <baskamer@gmail.com>
 * @license   Proprietary License
 */

namespace HF\ApiClient\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use Psr\Http\Message\ResponseInterface;

class PLHWProvider extends GenericProvider
{
    public function __construct(array $options = [], array $collaborators = [])
    {
        $options['scopeSeparator'] = ' ';

        parent::__construct($options, $collaborators);
    }

    /**
     * {@inheritdoc}
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (! empty($data['error'])) {
            $code  = 0;
            $error = $data['error_description'];
            throw new IdentityProviderException($error, $code, $data);
        }
    }
}
