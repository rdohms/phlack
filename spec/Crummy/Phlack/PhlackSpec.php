<?php

namespace spec\Crummy\Phlack;

use Crummy\Phlack\Bridge\Guzzle\Response\MessageResponse;
use Crummy\Phlack\Bridge\Guzzle\PhlackClient;
use Crummy\Phlack\Message\Message;
use Guzzle\Service\Client;
use Guzzle\Service\Command\OperationCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PhlackSpec extends ObjectBehavior
{
    static $mockUrl    = 'https://hooks.slack.com/services/XXXXXXX/YYYYYYY/ZZZZZZZ';
    static $mockConfig = ['username' => 'user', 'token' => 'token' ];

    function let(PhlackClient $client, OperationCommand $command, MessageResponse $response)
    {
        $client->execute($command)->willReturn($response);

        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Crummy\Phlack\Phlack');
    }

    function it_assumes_it_received_a_client_config_fromConfig()
    {
        $this->beConstructedThrough('fromConfig', [ ['url' => self::$mockUrl] ]);

        $this->getClient()->shouldReturnAnInstanceOf('Crummy\Phlack\Bridge\Guzzle\PhlackClient');
    }

    function it_assumes_it_received_a_url_when_constructed_with_a_string()
    {
        $this->beConstructedWith(self::$mockUrl);

        $this->getClient()->shouldReturnAnInstanceOf('Crummy\Phlack\Bridge\Guzzle\PhlackClient');
    }

    function it_assumes_it_received_a_client_config_when_constructed_with_an_array()
    {
        $this->beConstructedWith(self::$mockConfig);

        $this->getClient()->shouldReturnAnInstanceOf('Crummy\Phlack\Bridge\Guzzle\PhlackClient');
    }

    function it_fails_to_instantiate_with_an_invalid_client(Client $client)
    {
        $this->shouldThrow('\InvalidArgumentException')
            ->during('__construct', [$client]);
    }

    function it_assumes_url_when_factory_gets_a_single_argument()
    {
        $this::beConstructedThrough('factory', [ self::$mockUrl ]);

        $this->getClient()->getBaseUrl()->shouldBe(self::$mockUrl);
    }

    function its_factory_accepts_the_client_config()
    {
        $this->beConstructedThrough('factory', [ self::$mockConfig ]);

        $this->getClient()->shouldReturnAnInstanceOf('Crummy\Phlack\Bridge\Guzzle\PhlackClient');
    }

    function it_sends_messages($client, Message $message, OperationCommand $command, MessageResponse $response)
    {
        $message->jsonSerialize()->willReturn([ ]);

        $client->getCommand('Send', [ ])->willReturn($command);
        $client->execute($command)->willReturn($response);

        $this->send($message)->shouldReturn($response);
    }

    function it_returns_its_client($client)
    {
        $this->getClient()->shouldReturn($client);
    }

    function it_returns_a_message_builder()
    {
        $this->getMessageBuilder()->shouldReturnAnInstanceOf('\Crummy\Phlack\Builder\MessageBuilder');
    }

    function it_returns_an_attachment_builder()
    {
        $this->getAttachmentBuilder()->shouldReturnAnInstanceOf('\Crummy\Phlack\Builder\AttachmentBuilder');
    }
}
