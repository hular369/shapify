<?php

interface Animal {
    public function speak();
}

class Dog implements Animal {
    public function speak() {
        return "Woof!";
    }

    public function size() {
        return "medium";
    }
}

class Cat implements Animal {
    public function speak() {
        return "Meow!";
    }

    public function size() {
        return "small";
    }
}

class AnimalFactory {
    public static function create($animal) {
        if (class_exists($animal)) {
            return new $animal;
        } else {
            throw new Exception("Animal not found");
        }
    }
}

$animal = AnimalFactory::create('Cat');
echo $animal->speak(); // Output: Woof!
echo $animal->size(); // Output: small
