<?php

namespace App\ControllerArgumentResolver;

use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class Psr7RequestArgumentResolver implements ValueResolverInterface
{
    public function __construct(
        private HttpMessageFactoryInterface $httpMessageFactory,
        private RequestStack $requestStack,
    ) {
    }

    /**
     * @return iterable<ServerRequestInterface>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== ServerRequestInterface::class) {
            return [];
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest === null) {
            throw new RuntimeException('No request exists.');
        }

        yield $this->httpMessageFactory->createRequest($currentRequest);
    }
}
