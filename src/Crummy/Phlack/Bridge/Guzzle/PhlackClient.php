<?php

namespace Crummy\Phlack\Bridge\Guzzle;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

class PhlackClient extends Client
{
    /**
     * @param string $baseUrl
     * @param array $config
     */
    public function __construct($baseUrl = '', $config = [])
    {
        $baseUrl    = empty($baseUrl) ? PhlackPlugin::BASE_URL : $baseUrl;
        $default    = [ 'request.options' => [ 'exceptions'  => false ] ];
        $required   = [ 'username', 'token' ];
        $config     = Collection::fromConfig($config, $default, $required);

        parent::__construct($baseUrl, $config);

        $this
            ->setDescription(ServiceDescription::factory(__DIR__.'/Resources/slack.json'))
            ->addSubscriber(new PhlackPlugin($config['username'], $config['token']));
        ;
    }
}
