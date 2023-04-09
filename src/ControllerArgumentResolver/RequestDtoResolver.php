<?php

namespace App\ControllerArgumentResolver;

use App\Attribute\InitializeTo;
use App\Attribute\RequestDTO;
use BackedEnum;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

final readonly class RequestDtoResolver implements ValueResolverInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @phpstan-return iterable<object>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() === null || !class_exists($argument->getType())) {
            return [];
        }
        if (!$this->hasRequestDtoAttribute($argument->getType()) || !$request->getContent()) {
            return [];
        }

        assert(is_string($request->getContent()));
        $content = json_decode($request->getContent(), true);
        assert(is_array($content));

        assert(is_string($argument->getType()));

        yield $this->parse($argument->getType(), $content);
    }

    /**
     * @param array<mixed> $data
     */
    private function parse(string $type, array $data): object
    {
        $reflection = new ReflectionClass($type);

        try {
            $object = new $type();
        } catch (Error) {
            assert(class_exists($type));
            $object = $reflection->newInstanceWithoutConstructor();
        }

        foreach ($data as $key => $value) {
            try {
                $object->{$key} = $value;
            } catch (Error) {
                $property = $reflection->getProperty($key);
                if ($type = $property->getType()) {
                    if ($type instanceof ReflectionNamedType && !$type->isBuiltin() && $value !== null) {
                        if (is_a($type->getName(), AbstractUid::class, true)) {
                            $class = $type->getName();
                            assert(class_exists($class) && method_exists($class, 'fromString'));
                            assert(is_string($value));
                            $value = $class::fromString($value);
                        }
                        if (is_a($type->getName(), BackedEnum::class, true)) {
                            $class = $type->getName();
                            assert(is_a($class, BackedEnum::class, true));
                            assert(is_string($value) || is_int($value));
                            $value = $class::from($value);
                        }
                        if ($this->isDoctrineEntity($type->getName())) {
                            assert(class_exists($type->getName()));
                            assert(is_string($value));
                            $repository = $this->entityManager->getRepository($type->getName());
                            $id = Uuid::fromString($value);
                            $value = $repository->find($id->toBinary());
                        }
                        if ($this->hasRequestDtoAttribute($type->getName())) {
                            assert(is_array($value));
                            $value = $this->parse($type->getName(), $value);
                        }
                    }
                }
                $property->setValue($object, $value);
            }
        }

        foreach ($reflection->getProperties() as $property) {
            if ($property->isInitialized($object)) {
                continue;
            }
            if ($initializeTo = $this->findInitializeToAttribute($property)) {
                $property->setValue($object, $initializeTo->value);
            }
        }

        return $object;
    }

    private function hasRequestDtoAttribute(string $type): bool
    {
        if (!class_exists($type)) {
            return false; // @codeCoverageIgnore
        }
        $reflection = new ReflectionClass($type);

        return count($reflection->getAttributes(RequestDTO::class)) > 0;
    }

    private function findInitializeToAttribute(ReflectionProperty $property): ?InitializeTo
    {
        $attributes = $property->getAttributes(InitializeTo::class);
        if (!count($attributes)) {
            return null;
        }

        return $attributes[array_key_first($attributes)]->newInstance();
    }

    private function isDoctrineEntity(string $type): bool
    {
        if (!class_exists($type)) {
            return false; // @codeCoverageIgnore
        }

        return !$this->entityManager->getMetadataFactory()->isTransient($type);
    }
}
