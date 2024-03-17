<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionEnum;
use PHPUnit\Framework\TestCase;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;

abstract class AbstractMockeryRector extends AbstractRector
{
    public function __construct(
        public readonly ReflectionResolver $reflectionResolver,
        public readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function extendsClass(Class_ $class, string $parentClassName): bool
    {
        if (! $class->extends instanceof Name) {
            return false;
        }
        return $this->isSubclassOf($class, $parentClassName);
        // return $this->nodeNameResolver->isName($class->extends, $parentClassName);
    }

    public function hasAttribute(Class_ $class, string $attributeName): bool
    {
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if (! $this->nodeNameResolver->isName($attr->name, $attributeName)) {
                    continue;
                }
                return true;
            }
        }
        return false;
    }

    public function hasConstant(Class_ $class, string $constantName): bool
    {
        foreach ($class->getConstants() as $constant) {
            if (! $this->nodeNameResolver->isName($constant, $constantName)) {
                continue;
            }
            return true;
        }
        return false;
    }

    public function hasInterface(Class_ $class, string $interfaceName): bool
    {
        foreach ($class->implements as $interface) {
            if (! $this->nodeNameResolver->isName($interface, $interfaceName)) {
                continue;
            }
            return true;
        }
        return false;
    }

    public function hasMethod(Class_ $class, string $methodName): bool
    {
        foreach ($class->getMethods() as $method) {
            if (! $this->nodeNameResolver->isName($method->name, $methodName)) {
                continue;
            }
            return true;
        }
        return false;
    }

    public function hasProperty(Class_ $class, string $propertyName): bool
    {
        foreach ($class->getProperties() as $property) {
            if (! $this->nodeNameResolver->isName($property->props[0]->name, $propertyName)) {
                continue;
            }
            return true;
        }
        return false;
    }

    public function hasTrait(Class_ $class, string $desiredTrait): bool
    {
        foreach ($class->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $traitName) {
                if (! $this->nodeNameResolver->isName($traitName, $desiredTrait)) {
                    continue;
                }
                return true;
            }
        }
        return false;
    }

    public function isAbstract(Class_ $node): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }
        return $classReflection->isAbstract();
    }

    public function isFinalByKeyword(Class_ $node): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }
        return $classReflection->isFinalByKeyword();
    }

    public function isPHPUnitTestCase(Class_ $node): bool
    {
        return $this->isSubclassOf($node, TestCase::class);
    }

    public function isSubclassOf(Class_ $node, string $class): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);

        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->isSubclassOf($class);
    }

    /**
     * @param Stmt[] $stmts
     *
     * @return Stmt[]
     */
    public function removeStmts(ClassMethod $classMethod, array $stmts): array
    {
        $this->traverseNodesWithCallable(
            (array) $classMethod->stmts,
            function (Node $node) use (&$stmts) {
                foreach ($stmts as $key => $assign) {
                    if (! $this->nodeComparator->areNodesEqual($node, $assign)) {
                        continue;
                    }
                    unset($stmts[$key]);
                }
                return null;
            }
        );
        return $stmts;
    }

    public function resolveParentClassName(Class_ $node): ?string
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $nativeReflection = $classReflection->getNativeReflection();
        if ($nativeReflection instanceof ReflectionEnum) {
            return null;
        }
        return $nativeReflection->getParentClassName();
    }

    private function hasClassParentClassMethod(Class_ $class, string $methodName): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($class);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }
        foreach ($classReflection->getParents() as $parentClassReflection) {
            if ($parentClassReflection->hasMethod($methodName)) {
                return true;
            }
        }
        return false;
    }

    private function hasMethodParameter(ClassMethod $classMethod, string $name): bool
    {
        foreach ($classMethod->params as $param) {
            if ($this->nodeNameResolver->isName($param->var, $name)) {
                return true;
            }
        }
        return false;
    }
}
