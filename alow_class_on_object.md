
###  Allowing `::class` on objects [RFC](https://wiki.php.net/rfc/class_name_literal_on_object)

A small, yet useful, new feature: it\'s now possible to use `::class` on
objects, instead of having to use `get_class()` on them. It works the
same way as `get_class()`.

    $foo = new Foo();

    var_dump($foo::class);
