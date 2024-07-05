<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionEnum;
use PHPStan\Reflection\ClassReflection;
use PHPUnit\Framework\TestCase;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\CodingStyle\Application\UseImportsAdder;
use Rector\CodingStyle\ClassNameImport\ClassNameImportSkipper;
use Rector\CodingStyle\Node\NameImporter;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Exception\ShouldNotHappenException;
use Rector\Naming\Naming\AliasNameResolver;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockNameImporter;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\Application\File;

use function array_unshift;
use function count;

abstract class AbstractMockeryRector extends AbstractRector
{
    /**
     * Remove a node that occurs in an array.
     */
    final public const REMOVE_NODE = NodeTraverser::REMOVE_NODE;

    /**
     * Stop traversing the current node.
     */
    final public const STOP_TRAVERSAL = NodeTraverser::STOP_TRAVERSAL;

    final public function __construct(
        public readonly AliasNameResolver $aliasNameResolver,
        public readonly BetterNodeFinder $betterNodeFinder,
        public readonly ClassNameImportSkipper $classNameImportSkipper,
        public readonly DocBlockNameImporter $docBlockNameImporter,
        public readonly DocBlockUpdater $docBlockUpdater,
        public readonly NameImporter $nameImporter,
        public readonly PhpDocInfoFactory $phpDocInfoFactory,
        public readonly ReflectionResolver $reflectionResolver,
        public readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        public readonly UseImportsAdder $useImportsAdder,
        public readonly UseImportsResolver $useImportsResolver,
        public readonly UseNodesToAddCollector $useNodesToAddCollector,
    ) {
    }

    /**
     * @throws ShouldNotHappenException
     */
    final public function addMockeryPHPUnitIntegrationTrait(FileWithoutNamespace|Namespace_ $node): Node
    {
        $this->traverseNodes(
            $node->stmts,
            function (Node $node) use (&$refactored): ?Node {
                if (! $node instanceof Class_) {
                    return null;
                }

                foreach ($node->stmts as $key => $classMethod) {
                    if (! $classMethod instanceof ClassMethod) {
                        continue;
                    }

                    if (! $this->isName($classMethod, 'tearDown')) {
                        continue;
                    }

                    $stmtsCount = count($classMethod->stmts);
                    if ($stmtsCount > 2) {
                        continue;
                    }

                    if (! $this->hasMockeryCloseStaticCall($classMethod)) {
                        continue;
                    }

                    if ($stmtsCount === 2 && ! $this->hasParentTearDownStaticCall($classMethod)) {
                        continue;
                    }

                    unset($node->stmts[$key]);
                }

                $this->addTraitUse($node, MockeryPHPUnitIntegration::class);

                return $node;
            }
        );

        return $node;
    }

    /**
     * @throws ShouldNotHappenException
     */
    final public function addTraitUse(Class_ $node, string $class): void
    {
        array_unshift($node->stmts, new TraitUse([$this->importName($class)]));
    }

    /**
     * @throws ShouldNotHappenException
     */
    final public function currentFile(): File
    {
        return $this->file;
    }

    final public function extend(Class_ $node, string $class): void
    {
        $node->extends = $this->importName($class);
    }

    final public function extendsClass(Class_ $class, string $parentClassName): bool
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

    final public function hasAttribute(Class_ $class, string $attributeName): bool
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

    final public function hasClassParentClassMethod(Class_ $class, string $methodName): bool
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

    final public function hasConstant(Class_ $class, string $constantName): bool
    {
        foreach ($class->getConstants() as $classConst) {
            if (! $this->nodeNameResolver->isName($classConst, $constantName)) {
                continue;
            }

            return true;
        }

        return false;
    }

    final public function hasInterface(Class_ $class, string $interfaceName): bool
    {
        foreach ($class->implements as $interface) {
            if (! $this->nodeNameResolver->isName($interface, $interfaceName)) {
                continue;
            }

            return true;
        }

        return false;
    }

    final public function hasMethod(Class_ $class, string $methodName): bool
    {
        foreach ($class->getMethods() as $classMethod) {
            if (! $this->nodeNameResolver->isName($classMethod->name, $methodName)) {
                continue;
            }

            return true;
        }

        return false;
    }

    final public function hasMethodParameter(ClassMethod $classMethod, string $name): bool
    {
        foreach ($classMethod->params as $param) {
            if ($this->nodeNameResolver->isName($param->var, $name)) {
                return true;
            }
        }

        return false;
    }

    final public function hasMockeryCloseAndParentTearDown(ClassMethod $classMethod): bool
    {
        return $this->hasMockeryCloseStaticCall($classMethod) && $this->hasParentTearDownStaticCall($classMethod);
    }

    final public function hasMockeryCloseStaticCall(ClassMethod $classMethod): bool
    {
        return $this->betterNodeFinder->findFirst($classMethod, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, 'Mockery')) {
                return false;
            }

            return $this->isName($node->name, 'close');
        }) instanceof Node;
    }

    final public function hasMockeryGlobalMockFunctionCall(Class_ $class): bool
    {
        return $this->betterNodeFinder->findFirst($class, function (Node $node): bool {
            if (! $node instanceof FuncCall) {
                return false;
            }

            return $this->isName($node->name, 'mock');
        }) instanceof Node;
    }

    final public function hasMockeryMockStaticCall(Class_ $class): bool
    {
        return $this->betterNodeFinder->findFirst($class, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, 'Mockery')) {
                return false;
            }

            return $this->isName($node->name, 'mock');
        }) instanceof Node;
    }

    final public function hasMockeryPHPUnitIntegrationTrait(Class_ $class): bool
    {
        //        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        //
        //        if (! $classReflection instanceof ClassReflection) {
        //            return false;
        //        }
        //
        //        return $classReflection->hasTraitUse(MockeryPHPUnitIntegration::class);
        return $this->hasTrait($class, MockeryPHPUnitIntegration::class);
    }

    final public function hasParentTearDownStaticCall(ClassMethod $classMethod): bool
    {
        return $this->betterNodeFinder->findFirst($classMethod, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, 'parent')) {
                return false;
            }

            return $this->isName($node->name, 'tearDown');
        }) instanceof Node;
    }

    final public function hasProperty(Class_ $class, string $propertyName): bool
    {
        foreach ($class->getProperties() as $property) {
            if (! $this->nodeNameResolver->isName($property->props[0]->name, $propertyName)) {
                continue;
            }

            return true;
        }

        return false;
    }

    final public function hasTestCaseTearDownMockeryCloseWithOptionalParentTearDown(Class_ $class): bool
    {
        return $this->betterNodeFinder->findFirst($class, function (Node $node): bool {
            if (! $node instanceof ClassMethod) {
                return false;
            }

            if (! $this->isName($node->name, 'tearDown')) {
                return false;
            }

            $stmtsCount = count($node->stmts ?? []);

            if ($stmtsCount === 2) {
                return $this->hasMockeryCloseAndParentTearDown($node);
            }

            return $stmtsCount === 1 ? $this->hasMockeryCloseStaticCall($node) : false;
        }) instanceof Node;
    }

    final public function hasTrait(Class_ $class, string $desiredTrait): bool
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

    /**
     * @template T of object
     *
     * @param class-string<T>|list<string>|Name|string $fullyQualifiedClassName
     *
     * @throws ShouldNotHappenException
     *
     * @return ?Name
     *
     */
    final public function importName(array|Name|string $fullyQualifiedClassName): ?Name
    {
        $file = $this->currentFile();

        $fullyQualified = new FullyQualified($fullyQualifiedClassName);

        if (
            $this->classNameImportSkipper
                ->shouldSkipName($fullyQualified, $this->useImportsResolver->resolve())
        ) {
            return null;
        }

        return $this->nameImporter->importName($fullyQualified, $file);
    }

    final public function isAbstract(Class_ $class): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($class);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->isAbstract();
    }

    final public function isFinalByKeyword(Class_ $class): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($class);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->isFinalByKeyword();
    }

    final public function isSubclassOf(Class_ $node, string $class): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);

        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->isSubclassOf($class);
    }

    final public function isSubclassOfMockeryPHPUnitTestCase(Class_ $class): bool
    {
        return $this->isSubclassOf($class, MockeryTestCase::class);
    }

    final public function isSubclassOfPHPUnitTestCase(Class_ $class): bool
    {
        return $this->isSubclassOf($class, TestCase::class);
    }

    final public function needsMockeryPHPUnitIntegrationTrait(FileWithoutNamespace|Namespace_ $node): bool
    {
        $result = false;

        $this->traverseNodesWithCallable(
            $node->stmts,
            function (Node $node) use (&$result): ?Node {
                if (! $node instanceof Class_) {
                    return null;
                }

                if ($this->hasMockeryPHPUnitIntegrationTrait($node)) {
                    return null;
                }

                if (! $this->isSubclassOfPHPUnitTestCase($node)) {
                    return null;
                }

                $result = $this->hasMockeryMockStaticCall($node)
                    || $this->hasMockeryGlobalMockFunctionCall($node);

                return null;
            }
        );

        return $result;
    }

    final public function removeClassMethod(Class_ $class, string $methodName): Class_
    {
        $this->traverseNodes(
            $class->stmts,
            function (Node $node) use ($methodName): ?int {
                if (! $node instanceof ClassMethod) {
                    return null;
                }

                if (! $this->isName($node, $methodName)) {
                    return null;
                }

                return self::REMOVE_NODE;
            }
        );
        return $class;
    }

    final public function removeClassMethodTeardown(Class_ $class): void
    {
        $this->removeClassMethod($class, 'tearDown');
    }

    /**
     * @param Stmt[] $stmts
     *
     * @throws ShouldNotHappenException
     *
     * @return Stmt[]
     *
     */
    final public function removeStmts(ClassMethod $classMethod, array &$stmts): array
    {
        foreach ($stmts as $key => $assign) {
            if (! $this->nodeComparator->areNodesEqual($classMethod, $assign)) {
                continue;
            }

            unset($stmts[$key]);
        }

        return $stmts;
    }

    /**
     * @template T of object
     *
     * @param list<class-string<T>> $removedUseStatements
     *
     * @throws ShouldNotHappenException
     */
    final public function removeUseStatements(string ...$removedUseStatements): void
    {
        $this->traverseFile(
            function (Node $node) use ($removedUseStatements): ?int {
                if (! $node instanceof Use_) {
                    return null;
                }

                foreach ($node->uses as $usesKey => $useUse) {
                    if (! $this->isNames($useUse->name, $removedUseStatements)) {
                        continue;
                    }

                    unset($node->uses[$usesKey]);
                }

                return $node->uses === [] ? self::REMOVE_NODE : null;
            }
        );
    }

    final public function resolveParentClassName(Class_ $class): ?string
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($class);
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
     * @param callable(Node):(null|int|list<Node>|Node) $callback
     *
     * @throws ShouldNotHappenException
     */
    final public function traverseFile(callable $callback): void
    {
        $this->simpleCallableNodeTraverser
            ->traverseNodesWithCallable($this->currentFile()->getNewStmts(), $callback);
    }

    /**
     * @param callable(Node):(null|int|list<Node>|Node) $callback
     * @param list<Node>                                $nodes
     */
    final public function traverseNodes(array $nodes, callable $callback): void
    {
        if ($nodes === []) {
            return;
        }

        $this->simpleCallableNodeTraverser
            ->traverseNodesWithCallable($nodes, $callback);
    }

    final public function usesClass(): array
    {
        return $this->useNodesToAddCollector->getObjectImportsByFilePath($this->currentFile()->getFilePath());
    }

    final public function usesConstant(): array
    {
        return $this->useNodesToAddCollector->getConstantImportsByFilePath($this->currentFile()->getFilePath());
    }

    final public function usesFunction(): array
    {
        return $this->useNodesToAddCollector->getFunctionImportsByFilePath($this->currentFile()->getFilePath());
    }
}
