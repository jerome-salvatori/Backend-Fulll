<?php

namespace Fulll\Infra\Utils;

use Fulll\Domain\Create;
use Fulll\Domain\Register;
use Fulll\Domain\Park;
use Fulll\Domain\Query;

class Hydrator {
//uses __meta_data__ field in array to identify class to hydrate
    public function hydrate(array $resultArray): mixed {
        if (!array_key_exists("__meta_data__", $resultArray)) {
            throw new \InvalidArgumentException("Meta data must be provided for object to be instantiated with array data");
        }

        $resultArray = $this->propsToCamelCase($resultArray);
        [$class, $object] = $this->convertArrayToClass($resultArray);
        $this->setReflectionProps($resultArray, $class, $object);
        return $object;
    }

    private function propsToCamelCase(array $resultArray): array {
        $transformedArray = [];
        foreach($resultArray as $k => $v) {
            if ($k == "__meta_data__") {
                continue;
            }

            $newKey = str_replace("_", '', lcfirst(ucwords($k, "_")));
            $transformedArray[$newKey] = $v;
        }
        $transformedArray["__meta_data__"] = $resultArray["__meta_data__"];

        return $transformedArray;
    }

    private function setReflectionProps(array $props, \ReflectionClass $class, mixed $object): void {
        foreach ($props as $prop => $value) {
            if (!is_string($prop)) {
                throw new \InvalidArgumentException("Props names supplied to setReflectionProps method must be strings");
            }

            $reflectionProp = $class->getProperty($prop);
            $reflectionProp->setAccessible(true);
            if (is_array($value) && array_key_exists("__meta_data__", $value)) {
                [$joinedClass, $joinedObject] = $this->convertArrayToClass($value);
                $this->setReflectionProps($value, $joinedClass, $joinedObject);
                $value = $joinedObject;
            } elseif (is_array($value)) {
                $newCollectionArray = [];
                foreach($value as $collectionClassArray) {
                    if (array_key_exists("__meta_data__", $collectionClassArray)) {
                        [$collectionClass, $collectionObject] = $this->convertArrayToClass($collectionClassArray);
                        $this->setReflectionProps($collectionClassArray, $collectionClass, $collectionObject);
                        $newCollectionArray[] = $collectionObject;
                    }
                }
                $value = $newCollectionArray;
            }
            $reflectionProp->setValue($object, $value);
        }
    }

    private function convertArrayToClass(array &$array): array {
        $class = new \ReflectionClass($array["__meta_data__"]);
        unset($array["__meta_data__"]);
        $object = $class->newInstanceWithoutConstructor();

        return [$class, $object];
    }

    public function convertObjectToArray($object): array {
        if (!is_object($object)) {
            throw new \InvalidArgumentException("Argument given to hydrator's convertObjectToArray method must be an object");
        }

        $fullyQualifiedName = get_class($object);
        $reflectionClass = new \ReflectionClass($fullyQualifiedName);
        $resultArray = [];
        foreach($reflectionClass->getProperties() as $prop) {
            $propValue = $prop->getValue($object);
            if (is_array($propValue)) {
                $subArray = [];
                foreach ($propValue as $collectionObject) {
                    $subArray[] = $this->convertObjectToArray($collectionObject);
                }
                $propValue = $subArray;
            }
            $resultArray[$prop->getName()] = $propValue;
        }
        $resultArray["__meta_data__"] = $fullyQualifiedName;

        return $resultArray;
    }
}