### New `get_debug_type()` function [RFC](https://wiki.php.net/rfc/get_debug_type)

`get_debug_type()` returns the type of a variable. Sounds like something
`gettype()` would do? `get_debug_type()` returns more useful output for
arrays, strings, anonymous classes and objects.

For example, calling `gettype()` on a class `\Foo\Bar` would return
`object`. Using `get_debug_type()` will return the class name.

A full list of differences between `get_debug_type()` and `gettype()`
can be found in the RFC.
