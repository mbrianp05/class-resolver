<?php

namespace Laton\Framework\Kernel\ClassResolver;

class DirectoryClassResolver
{
    public function __construct(protected string $directory)
    {
    }

    public function getClasses(): array
    {
        if (!is_dir($this->directory)) {
            throw new \LogicException(\sprintf('Invalid directory %s', $this->directory));
        }

        chdir($this->directory);
        
        $files = glob('*.php');
        $classes = [];

        foreach ($files as $file) {
            $file = $this->directory . '\\' . $file;
            $code = \file_get_contents($file);

            $cr = new ClassResolver($code);

            if (null !== $cr->getName()) {
                $classes[$file] = $cr->getName();
            }
        }

        return $classes;
    }
}