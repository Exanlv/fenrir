<?php

declare(strict_types=1);

namespace Ragnarok\Fenrir\Rest;

use Discord\Http\Endpoint;
use Ragnarok\Fenrir\Interaction\Helpers\InteractionCallbackBuilder;
use Ragnarok\Fenrir\Parts\Message;
use Ragnarok\Fenrir\Rest\Helpers\Webhook\EditWebhookBuilder;
use React\Promise\ExtendedPromiseInterface;

/**
 * @see https://discord.com/developers/docs/resources/webhook
 */
class Webhook extends HttpResource
{
    /**
     * @see https://discord.com/developers/docs/interactions/receiving-and-responding#create-interaction-response
     */
    public function createInteractionResponse(
        string $interactionId,
        string $interactionToken,
        InteractionCallbackBuilder $interactionCallbackBuilder
    ): ExtendedPromiseInterface {
        return $this->http->post(
            Endpoint::bind(
                Endpoint::INTERACTION_RESPONSE,
                $interactionId,
                $interactionToken
            ),
            $interactionCallbackBuilder->get()
        )->otherwise($this->logThrowable(...));
    }

    /**
     * @see https://discord.com/developers/docs/interactions/receiving-and-responding#get-original-interaction-response
     */
    public function getOriginalInteractionResponse(
        string $applicationId,
        string $interactionToken
    ): ExtendedPromiseInterface {
        return $this->mapPromise(
            $this->http->get(
                Endpoint::bind(
                    Endpoint::ORIGINAL_INTERACTION_RESPONSE,
                    $applicationId,
                    $interactionToken,
                )
            ),
            Message::class
        )->otherwise($this->logThrowable(...));
    }

    /**
     * @see https://discord.com/developers/docs/interactions/receiving-and-responding#edit-original-interaction-response
     */
    public function editOriginalInteractionResponse(
        string $applicationId,
        string $interactionToken,
        EditWebhookBuilder $webhookBuilder
    ): ExtendedPromiseInterface {
        return $this->mapPromise(
            $this->http->patch(
                Endpoint::bind(
                    Endpoint::ORIGINAL_INTERACTION_RESPONSE,
                    $applicationId,
                    $interactionToken,
                ),
                $webhookBuilder->get()
            ),
            Message::class
        )->otherwise($this->logThrowable(...));
    }

    /**
     * @see https://discord.com/developers/docs/interactions/receiving-and-responding#delete-original-interaction-response
     */
    public function deleteOriginalInteractionResponse(
        string $applicationId,
        string $interactionToken
    ): ExtendedPromiseInterface {
        return $this->http->delete(
            Endpoint::bind(
                Endpoint::ORIGINAL_INTERACTION_RESPONSE,
                $applicationId,
                $interactionToken
            )
        )->otherwise($this->logThrowable(...));
    }
}
