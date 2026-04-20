This is a fork of the project:  
https://github.com/imanghafoori1/eloquent-mockery  
**With unlocked Laravel version.**

# Eloquent Mockery

Mock your eloquent queries without the repository pattern.

### Why this package was invented?
- It solves the problem of "slow tests" by removing the interactions with a real database.
- It simplifies the process of writing and running tests since your tests will be "DB Independent".

## Installation
You can **install** the package via Composer:
```bash
composer require homedistil/eloquent-mockery --dev dev-main
```

## Usage:
First, you have to define a new connection in your `config/database.php` and set the driver to 'arrayDB'.

```php
<?php

return [
  
   ...
  
  'connections' => [
     'fake' => [
         'driver' => 'arrayDB',
         'database' => '',
     ],
     
     ...
  ],
  ...
]
```

Then you can:

```php
public function test_basic()
{
    config()->set('database.default', 'my_test_connection');

    # ::Arrange:: (Setup Sample Data)
    FakeDB::addRow('users', ['id' => 1, 'username' => 'faky', 'password' => '...']);
    FakeDB::addRow('users', ['id' => 1, 'username' => 'maky', 'password' => '...']);

    # ::Act:: (This query resides in your controller)
    $user = User::where('username', 'faky')->first();   # <=== This does NOT connect to a real DB.

    # ::Assert::
    $this->assert($user->id === 1);
    $this->assert($user->username === 'faky');
}
```


### Mocking a `create` query:
```php
public function test_basic()
{
    # In setUp:
    FakeDB::mockEloquentBuilder();

    # ::Act::
    $this->post('/create-url', ['some' => 'data' ])

    # ::Assert::
    $user = User::first();
    $this->assertEquals('iman', $user->username);

    # In tearDown
    FakeDB::dontMockEloquentBuilder();
}
```

- For more examples take a look at the `tests` directory.

<a name="license"></a>
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
