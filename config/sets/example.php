<?php

declare(strict_types=1);

use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use Rector\Arguments\Rector\MethodCall\RemoveMethodCallParamRector;
use Rector\Arguments\ValueObject\RemoveMethodCallParam;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassLike\RemoveAnnotationRector;
use Rector\Removing\Rector\Class_\RemoveInterfacesRector;
use Rector\Removing\Rector\Class_\RemoveTraitUseRector;
use Rector\Removing\Rector\ClassMethod\ArgumentRemoverRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallArgRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;
use Rector\Removing\ValueObject\ArgumentRemover;
use Rector\Removing\ValueObject\RemoveFuncCallArg;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\Renaming\ValueObject\RenameClassConstFetch;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;

$directory = \getcwd() ?: __DIR__;

require_once $directory . '/vendor/autoload.php';

return RectorConfig::configure()
    ->withSets([SetList::PHP_73])
    ->withConfiguredRule(RemoveAnnotationRector::class, ['method'])
    ->withConfiguredRule(RemoveFuncCallArgRector::class, [new RemoveFuncCallArg('remove_last_arg', 1)])
    ->withConfiguredRule(RemoveFuncCallRector::class, ['var_dump'])
    ->withConfiguredRule(RemoveFuncCallRector::class, ['var_dump'])
    ->withConfiguredRule(RemoveInterfacesRector::class, ['SomeInterface'])
    ->withConfiguredRule(RemoveInterfacesRector::class, ['Stringable'])
    ->withConfiguredRule(RemoveMethodCallParamRector::class, [new RemoveMethodCallParam('SomeClass', 'someMethod', 0)])
    ->withConfiguredRule(RemoveTraitUseRector::class, ['TraitNameToRemove'])
    ->withConfiguredRule(RemoveTraitUseRector::class, ['TraitToBeRemoved'])
    ->withConfiguredRule(RenameClassRector::class, [
        'OldName' => 'NewName',
        'App\\SomeOldClass' => 'App\\SomeNewClass',
    ])
    ->withConfiguredRule(RenameMethodRector::class, [
        new MethodCallRename('SomeExampleClass', 'oldMethod', 'newMethod'),
    ])
    ->withConfiguredRule(ArgumentRemoverRector::class, [
        new ArgumentRemover('ExampleClass', 'someMethod', 0, [true]),
    ])
    ->withConfiguredRule(RenameStaticMethodRector::class, [
        new RenameStaticMethod('SomeClass', 'oldMethod', 'AnotherExampleClass', 'newStaticMethod'),
    ])
    ->withConfiguredRule(RenameClassConstFetchRector::class, [
        new RenameClassConstFetch('SomeClass', 'OLD_CONSTANT', 'NEW_CONSTANT'),
        new RenameClassAndConstFetch('SomeClass', 'OTHER_OLD_CONSTANT', 'DifferentClass', 'NEW_CONSTANT'),
    ])
    ->withConfiguredRule(RemoveMethodCallParamRector::class, [
        new RemoveMethodCallParam('class', 'methodName', $paramPosition = 1),
        new RemoveMethodCallParam('class', 'methodName', $paramPosition = 3),
    ])
    ->withConfiguredRule(AddParamTypeDeclarationRector::class, [
        new AddParamTypeDeclaration('SomeClass', 'process', 0, new StringType()),
        new AddParamTypeDeclaration('ClassName', 'dump', 0, new ObjectType('OtherClassName')),
    ])
;
