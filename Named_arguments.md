<body style="font-size: large">

## Named arguments

[Named arguments](https://wiki.php.net/rfc/named_params) allow you to pass in
values to a function, by specifying the value name, so that you don't
have to take their order into consideration, and you can also skip
optional parameters!

````php
    function foo(string $a, string $b, ?string $c = null, ?string $d = null) 
    { /* … */ }
    
    foo(
        b: 'value b', 
        a: 'value a', 
        d: 'value d',
    );
````

Let's discuss their ins and outs,
but let me show you first what they look like with a few examples in the
wild:

````php
    setcookie(
        name: 'test',
        expires: time() + 60 * 60 * 2,
    );
````

*Named arguments used on a built-in PHP function*

````php
    class CustomerData
    {
        public function __construct(
            public string $name,
            public string $email,
            public int $age,
        ) {}
    }
    
    $data = new CustomerData(
        name: $input['name'],
        email: $input['email'],
        age: $input['age'],
    );
````

*A DTO making use of [promoted
properties](/blog/constructor-promotion-in-php-8), as well as named
arguments*

````php
    $data = new CustomerData(...$customerRequest->validated());
````

*Named arguments also support array spreading*

You might have guessed it from the examples: named arguments allow you
to pass input data into a function, based on their argument name instead
of the argument order.

I would argue named arguments are a great feature that will have a
significant impact on my day-to-day programming life. You're probably
wondering about the details though: what if you pass a wrong name,
what's up with that array spreading syntax? Well, let's look at all
those questions in-depth.

##  Why named arguments?

Let's say this feature was a highly debated one, and there were some
[counter arguments](/blog/why-we-need-named-params-in-php) to not adding
them. However, I'd say their benefit far outweigh the fear of backwards
compatibility problems or bloated APIs. The way I see it, they will
allow us to write cleaner and more flexible code.

For one, named arguments allow you to skip default values. Take a look
again at the cookie example:
````php
    setcookie(
        name: 'test',
        expires: time() + 60 * 60 * 2,
    );
````
Its method signature is actually the following:
````php
    setcookie ( 
        string $name, 
        string $value = "", 
        int $expires = 0, 
        string $path = "", 
        string $domain = "", 
        bool $secure = false, 
        bool $httponly = false,
    ) : bool
````
In the example I showed, we didn't need to set the a cookie `$value`,
but we did need to set an expiration time. Named arguments made this
method call a little more concise:
````php
    setcookie(
        'test',
        '',
        time() + 60 * 60 * 2,
    );
````
*`setcookie` without named arguments*
````php
    setcookie(
        name: 'test',
        expires: time() + 60 * 60 * 2,
    );
````
*`setcookie` with named arguments*

Besides skipping arguments with default values, there's also the benefit
of having clarity about which variable does what; something that's
especially useful in functions with large method signatures. Now we
could say that lots of arguments are usually a code smell; we still have
to deal with them no matter what, so it's better to have a sane way of
doing so, than nothing at all.

##  Named arguments in depth

With the basics out of the way, let's look at what named arguments can
and can't do.

First of all, named arguments can be combined with unnamed — also called
ordered — arguments. In that case the ordered arguments must always come
first.

Take our DTO example from before:
````php
    class CustomerData
    {
        public function __construct(
            public string $name,
            public string $email,
            public int $age,
        ) {}
    }
````
You could construct it like so:
````php
    $data = new CustomerData(
        $input['name'],
        age: $input['age'],
        email: $input['email'],
    );
````
However, having an ordered argument after a named one would throw an
error:
````php
    $data = new CustomerData(
        age: $input['age'],
        $input['name'],
        email: $input['email'],
    );
````
-----

Next, it's possible to use array spreading in combination with named
arguments:
````php
    $input = [
        'age' => 25,
        'name' => 'Brent',
        'email' => 'brent@stitcher.io',
    ];
    
    $data = new CustomerData(...$input);
````
*If*, however, there are missing required entries in the array, or if
there's a key that's not listed as a named argument, an error will be
thrown:
````php
    $input = [
        'age' => 25,
        'name' => 'Brent',
        'email' => 'brent@stitcher.io',
        'unknownProperty' => 'This is not allowed',
    ];
    
    $data = new CustomerData(...$input);
````
It *is* possible to combine named and ordered arguments in an input
array, but only if the ordered arguments follow the same rule as before:
they must come first!
````php
    $input = [
        'Brent',
        'age' => 25,
        'email' => 'brent@stitcher.io',
    ];
    
    $data = new CustomerData(...$input);
````

If you're using variadic functions, named arguments will be passed with
their key name into the variadic arguments array. Take the following
example:
````php
    class CustomerData
    {
        public static function new(...$args): self
        {
            return new self(...$args);
        }
    
        public function __construct(
            public string $name,
            public string $email,
            public int $age,
        ) {}
    }
    
    $data = CustomerData::new(
        email: 'brent@stitcher.io',
        age: 25,
        name: 'Brent',
    );
````
In this case, `$args` in `CustomerData::new` will contain the following
data:
````php
    [
        'age' => 25,
        'email' => 'brent@stitcher.io',
        'name' => 'Brent',
    ]
````


[Attributes](/blog/attributes-in-php-8) — also known as annotations —
also support named arguments:
````php
    class ProductSubscriber
    {
        #[ListensTo(event: ProductCreated::class)]
        public function onProductCreated(ProductCreated $event) { /* … */ }
    }
````
It's not possible to have a variable as the argument name:
````php
    $field = 'age';
    
    $data = CustomerData::new(
        $field: 25,
    );
````


And finally, named arguments will deal in a pragmatic way with name
changes during inheritance. Take this example:
````php
    interface EventListener {
        public function on($event, $handler);
    }
    
    class MyListener implements EventListener
    {
        public function on($myEvent, $myHandler)
        {
            // …
        }
    }
````
PHP will silently allow changing the name of `$event` to `$myEvent`, and
`$handler` to `$myHandler`; *but* if you decide to use named arguments
using the parent's name, it will result in a runtime error:
````php
    public function register(EventListener $listener)
    {
        $listener->on(
            event: $this->event,
            handler: $this->handler, 
        );
    }
````
*Runtime error in case `$listener` is an instance of `MyListener`*

This pragmatic approach was chosen to prevent a major breaking change
when all inherited arguments would have to keep the same name. Seems
like a good solution to me.



That's most there is to tell about named arguments. If you want to know
a little more backstory behind some design decisions, I'd encourage you
to read [the RFC](https://wiki.php.net/rfc/named_params).

