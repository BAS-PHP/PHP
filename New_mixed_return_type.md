<body style="font-size: large">

## New `mixed` type

Some might call it a necessary evil: the `mixed` type causes many to
have mixed feelings. There's a very good argument to make for it though:
a missing type can mean lots of things in PHP:

- A function returns nothing or null
- We're expecting one of several types
- We're expecting a type that can't be type hinted in PHP

Because of the reasons above, it's a good thing the `mixed` type is
added. `mixed` itself means one of these types:

- `array`
- `bool`
- `callable`
- `int`
- `float`
- `null`
- `object`
- `resource`
- `string`

Note that `mixed` can also be used as a parameter or property type, not
just as a return type.

Also note that since `mixed` already includes `null`, it's not allowed
to make it nullable. The following will trigger an error:

````php
    // Fatal error: Mixed types cannot be nullable, null is already part of the mixed type.
    function bar(): ?mixed {}