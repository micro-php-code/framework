<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Attribute\Scanner;

use ReflectionClass;
use ReflectionException;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class AttributeScanner
{
    private AttributeScannerMap $map;

    public function __construct()
    {
        $this->map = new AttributeScannerMap();
    }

    /**
     * @throws ReflectionException
     */
    public function scan(array $directories): AttributeScannerMap
    {
        $finder = new Finder();
        $finder->in($directories)
            ->files()
            ->name('*.php');
        foreach ($finder as $file) {
            $reflectionClass = $this->getReflectClassFromFile($file);
            if ($reflectionClass) {
                $this->getAttributes($reflectionClass);
            }
        }

        return $this->map;
    }

    /**
     * @throws ReflectionException
     */
    private function getReflectClassFromFile(SplFileInfo $file): ?ReflectionClass
    {
        $content = file_get_contents($file->getRealPath());

        $namespace = '';

        $tokens = token_get_all($content);

        for ($i = 0; $i < count($tokens); $i++) {

            // 读取命名空间
            if (T_NAMESPACE === $tokens[$i][0]) {
                $namespace = $this->getNamespace($tokens, $i);
            }

            // 读取类名
            if (T_CLASS === $tokens[$i][0]) {
                return $this->getReflectionClass($tokens[$i + 2][1], $namespace);
            }
        }

        return null;
    }

    /**
     * 获取命名空间
     */
    private function getNamespace(array $tokens, int $namespaceIndex): string
    {
        $namespace = '';
        for ($i = $namespaceIndex + 1; $i < count($tokens); $i++) {
            if (';' === $tokens[$i]) {
                break;
            }
            $namespace .= $tokens[$i][1];
        }

        return trim($namespace);
    }

    /**
     * 创建反射类对象
     *
     * @throws ReflectionException
     */
    private function getReflectionClass(string $className, string $namespace): ReflectionClass
    {
        $fullName = trim($namespace, '\\') . '\\' . $className;

        return new ReflectionClass($fullName);
    }

    private function getAttributes(ReflectionClass $class): void
    {
        $attributes = $class->getAttributes();
        foreach ($attributes as $attribute) {
            $this->map->add($class->getName(), $attribute->getName(), $attribute->getArguments());
        }
    }
}
