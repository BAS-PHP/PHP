<body style="font-size: large">

## Abstract methods in traits improvements

Traits can specify abstract methods which must be implemented by the
classes using them. There's a caveat though: before PHP 8 the signature
of these method implementations weren't validated. The following was
valid:

````php
    trait Test {
        abstract public function test(int $input): int;
    }
    
    class UsesTrait
    {
        use Test;
    
        public function test($input)
        {
            return $input;
        }
    }
````

PHP 8 will perform proper method signature validation when using a trait
and implementing its abstract methods. This means you'll need to write
this instead:

````php
    class UsesTrait
    {
        use Test;
    
        public function test(int $input): int
        {
            return $input;
        }
    }
````