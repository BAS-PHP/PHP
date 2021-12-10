<body style="font-size: large">

## New `static` return type

While it was already possible to return `self`, `static` wasn't a valid
return type until PHP 8. Given PHP's dynamically typed nature, it's a
feature that will be useful to many developers.
````php
    class Foo
    {
        public function test(): static
        {
            return new static();
        }
    }
````

PHP class methods can return `self` and parent in previous versions, but `static` was not allowed in PHP versions prior to 8.0. The newly allowed `static` return type allows to narrow down the return type to the called class.

The `static` return type helps classes with fluent methods (i.e the ones with `return $this`), immutable classes (i.e `return clone $this`) or static methods that return an instance of the class itself.

Without the `static` type allowed in return types, one would have to use `self` as the return type, which may not be the ideal one. PHP DocBlock already allowed `@return static` in its DocBlocks to indicate that the methods return the object itself, or an instance of the same class.

With PHP 8.0's `static` return type support, it is now possible to replace DocBlock `@return static` statements with a return type declaration.

````php
class Foo {
-   /**
-    * @return static
-    */
-   public static getInstance() {
+   public static getInstance(): static {
        return new static();
    }
}
````

### Backwards Compatibility Impact

Code with `static` return type will not be backwards-compatible with older PHP versions prior to 8.0\. Doing so will result in a parse error:
````php
    Parse error: syntax error, unexpected 'static' (T_STATIC) in ... on line ...
````
It is not possible to back-port this feature to older PHP versions either.