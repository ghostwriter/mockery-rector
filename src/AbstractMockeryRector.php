<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionEnum;
use PHPUnit\Framework\TestCase;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\CodingStyle\Application\UseImportsAdder;
use Rector\CodingStyle\ClassNameImport\ClassNameImportSkipper;
use Rector\CodingStyle\Node\NameImporter;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Exception\ShouldNotHappenException;
use Rector\Naming\Naming\AliasNameResolver;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockNameImporter;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\Provider\CurrentFileProvider;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\Application\File;
use function in_array;

abstract class AbstractMockeryRector extends AbstractRector
{
    /**
     * Remove a node that occurs in an array.
     */
    public const REMOVE_NODE = 3;

    public function __construct(
        public readonly AliasNameResolver $aliasNameResolver,
        public readonly BetterNodeFinder $betterNodeFinder,
        public readonly ClassNameImportSkipper $classNameImportSkipper,
        public readonly CurrentFileProvider $currentFileProvider,
        public readonly DocBlockNameImporter $docBlockNameImporter,
        public readonly DocBlockUpdater $docBlockUpdater,
        public readonly NameImporter $nameImporter,
        public readonly PhpDocInfoFactory $phpDocInfoFactory,
        public readonly ReflectionResolver $reflectionResolver,
        public readonly UseImportsAdder $useImportsAdder,
        public readonly UseImportsResolver $useImportsResolver,
        public readonly UseNodesToAddCollector $useNodesToAddCollector,
    ) {
    }

    /**
     * @throws ShouldNotHappenException
     */
    final public function currentFile(): File
    {
        $file = $this->currentFileProvider->getFile();

        if (! $file instanceof File) {
            throw new ShouldNotHappenException();
        }

        return $file;
    }

    public function extendsClass(Class_ $class, string $parentClassName): bool
    {
        if (! $class->extends instanceof Name) {
            return false;
        }
        return $this->isSubclassOf($class, $parentClassName);
        // return $this->nodeNameResolver->isName($class->extends, $parentClassName);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FileWithoutNamespace::class, Namespace_::class];
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

    /**
     * @template T of object
     *
     * @param list<class-string<T>> $removedUseStatements
     */
    public function removeUseStatements(string ...$removedUseStatements): void
    {
        $this->traverse(
            static function (Node $node) use ($removedUseStatements): null|int {
                if (! $node instanceof Use_) {
                    return null;
                }

                foreach ($node->uses as $usesKey => $useUse) {
                    if (! in_array($useUse->name->toString(), $removedUseStatements, true)) {
                        continue;
                    }

                    unset($node->uses[$usesKey]);
                }

                if ($node->uses !== []) {
                    return null;
                }

                return self::REMOVE_NODE;
            }
        );
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

    /**
     * @param callable(Node):int|null|Node|list<Node> $callback
     */
    final public function traverse(callable $callback): void
    {
        $file = $this->currentFileProvider->getFile();

        if (! $file instanceof File) {
            throw new ShouldNotHappenException();
        }

        $stmts = $file->getNewStmts();

        $this->traverseNodesWithCallable($stmts, $callback);

        $file->changeNewStmts($stmts);
    }

    public function usesClass(): array
    {
        return $this->useNodesToAddCollector->getObjectImportsByFilePath($this->currentFile()->getFilePath());
    }

    public function usesConstant(): array
    {
        return $this->useNodesToAddCollector->getConstantImportsByFilePath($this->currentFile()->getFilePath());
    }

    public function usesFunction(): array
    {
        return $this->useNodesToAddCollector->getFunctionImportsByFilePath($this->currentFile()->getFilePath());
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $fullyQualifiedClassName
     *
     * @return ?Name
     */
    protected function importName(string $fullyQualifiedClassName): ?Name
    {
        $file = $this->currentFileProvider->getFile();

        if (! $file instanceof File) {
            return null;
        }

        $fullyQualified = new FullyQualified($fullyQualifiedClassName);

        if (
            $this->classNameImportSkipper->shouldSkipName($fullyQualified, $this->useImportsResolver->resolve())
        ) {
            return null;
        }

        return $this->nameImporter->importName($fullyQualified, $file);
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
