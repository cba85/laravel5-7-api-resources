# API Resources

## Basic

1. Install authentification
    ```bash
    $ php artisan make:auth
    $ php artisan migrate
    ```

    - Restart `php artisan serve`

    - In database/seed/DatabaseSeeder.php:

        https://laravel.com/docs/5.7/seeding

        ```php
        DB::table('users')->insert([
                'name' => str_random(10),
                'email' => str_random(10).'@gmail.com',
                'password' => bcrypt('secret'),
            ]);
        ```

2. Create a route

    - In routes/web.php:

        ```php
        Route::get('/user/{user}', function (\App\User $user) {
            return $user;
        });
        ```

    - In app/User.php:

        ```php
        protected $appends = ['humanCreatedAt'];

        public function getHumanCreatedAtAttribute()
        {
            return $this->created_at->diffForHumans();
        }
        ```

3. Create a resource

    ```bash
    $ php artisan make:resource UserResource
    ```

    - In routes/web.php:

    ```php
    Route::get('/user/{user}', function (\App\User $user) {
        return new \App\Http\Resources\UserResource($user);
    });
    ```

    - In app/Http/Resources/UserResource.php:

        ```php
        public function toArray($request)
        {
            //return parent::toArray($request);
            return [
                'id' => $this->id,
                'email' => $this->email
            ];
        }
        ```

## Static collection method

1. Create model and factory

```bash
$ php artisan make:model Topic -mf
```

2. Create table in database

    - In database/migrations:

        ```php
        public function up()
        {
            Schema::create('topics', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('title');
                $table->timestamps();
            });
        }
        ```

        ```bash
        $ php artisan migrate
        ```

    - [WORK] In database/seeders/DatabaseSeeder.php:

        ```php
        foreach(range(1, 10) as $i) {
            $topic->create([
                'title' => $faker->sentence(),
                'user_id' => 1
            ]);
        }
        foreach(range(1, 10) as $i) {
            $topic->create([
                'title' => $faker->sentence(),
                'user_id' => 2
            ]);
        }
        ```

    - [DOESN'T WORK] In database/factories/TopicFactory.php:

        ```php
        $factory->define(\App\Topic::class, function (Faker $faker) {
            return [
                'title' => $faker->sentence,
                'user_id' => 1,
            ];
        });
        ```

        ```bash
        $ php artisan tinker
        factory(\App\Topic::class, 10)->create()
        ```

3. Create resource

    - In routes/web.php:

        ```php
        Route::get('/topics', function () {
            return new \App\Http\Resources\TopicResource(\App\Topic::get());
        });
        ```

        ```bash
        $ php artisan make:resource TopicResource
        ```

    - In app/Http/Resources/TopicResource.php:

        ```php
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'title' => $this->title
            ];
        }
        ```

        ```php
        public function toArray($request) {
            dd($this->collection);
            dd($this->resource);
        ...
        }
        ```

    - In routes/web.php:

        ```php
        Route::get('/topics', function () {
            return \App\Http\Resources\TopicResource::collection(\App\Topic::get());
        });
        ```

## Resource collections

1. Create collection

    ```bash
    $ php artisan make:resource TopicCollection
    # or
    $ php artisan make:resource TopicResourceC --collection
    ```

2. Modify collection

    - In App/Http/Resources/TopicCollection.php:

        ```php
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'title' => $this->title
            ];
        }
        ```

    - In routes/web.php:

        ```php
        Route::get('/topics', function () {
            return new \App\Http\Resources\TopicCollection(\App\Topic::get());
        });
        ```

    - In App/Http/Resources/TopicCollection.php:

        ```php
        public function toArray($request)
        {
            //dd($this->collection);
            return [
                'data' => $this->collection,
                'meta' => [
                    'total' => $this->collection->count()
                ]
            ];
        }
        ```

        ```php
        public function toArray($request)
            {
            return [
                    'data' => TopicResource::collection($this->collection),
                    'meta' => [
                        'total' => $this->collection->count()
                    ]
                ];
        }
        ```

## Another way to add meta

- In routes/web.php:

    ```php
    Route::get('/u', function () {
        return new \App\Http\Resources\UserResource(\App\User::find(1));
    });
    ```

    ```php
    Route::get('/u', function () {
        return (new \App\Http\Resources\UserResource(\App\User::find(1)))->additional([
            'meta' => [
                'token' => '123456789'
            ]
        ]);
    });
    ```

## Pagination

- In routes/web.php:

    ```php
    Route::get('/topics', function () {
        return \App\Http\Resources\TopicResource::collection(\App\Topic::paginate(3));
    });
    ```

    ```php
        Route::get('/topics', function () {
        return new \App\Http\Resources\TopicCollection(\App\Topic::paginate(3));
    });
    ```

## Relationships

- Create a new Model:

    ```bash
    $ php artisan make:model Post -mf
    ```

- Migration in database/migrations:

    ```php
    Schema::create('posts', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id');
        $table->integer('topic_id');
        $table->text('body');
        $table->timestamps();
    });
    ```

    ```bash
    $ php artisan migrate
    ```

- Seed database:

    - In database/seeds/DatabaseSeeder.php:

        ```php
        foreach(range(1, 10) as $i) {
            $post->create([
                'topic_id' => 1,
                'body' => $faker->sentence(),
                'user_id' => 1
            ]);
        }
        ```

    - In routes/web.php:

        ```php
        Route::get('/topics', function () {
            return new \App\Http\Resources\TopicCollection(\App\Topic::get());
        });
        ```

    - In App/Http/Resources/TopicResource:

        ```php
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'title' => $this->title,
                'posts' => $this->posts,
            ];
        }
        ```

    - Create a Post resource:

        ```bash
        $ php artisan make:resource PostResource
        ```

    - In App/Http/Resources/TopicResource.php:

        ```php
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'title' => $this->title,
                'posts' => PostResource::collection($this->posts),
            ];
        }
        ```

    - In App\Http\Resource\PostResource.php:

        ```php
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'body' => $this->body,
                'user' => new UserResource($this->user)
            ];
        }
        ```

    - In App\Post.php:

        ```php
        public function user()
        {
            return $this->belongsTo(User::class);
        }
        ```

    - In App/Http/Resources/TopicResource.php:

        ```php
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'title' => $this->title,
                'posts' => PostResource::collection($this->posts),
                'user' => new UserResource($this->user)
            ];
        }
        ```

    - In App\Topic.php:

        ```php
        public function user()
        {
            return $this->belongsTo(User::class);
        }
        ```

## N+1 problem

- In routes/web.php:

    ```php
    DB::listen(function ($query) {
        dump($query->sql);
    });
    ```

    ```php
    Route::get('/topics', function () {
        return new \App\Http\Resources\TopicCollection(\App\Topic::with(['user', 'posts'])->get());
    });
    ```

## Conditionals

- In App/Http/Resources/TopicRessource.php:

    ```php
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'user' => new UserResource($this->user)
        ];
    }
    ```

- In routes/web.php:

    ```php
    return new \App\Http\Resources\TopicCollection(\App\Topic::with(['user'])->get());
    ```

- In App/Http/Resources/TopicRessource.php:

    ```php
    public function toArray($request)
    {
        $return [
            'id' => $this->id,
            'secret' => 'abc',
            'title' => $this->title,
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'user' => new UserResource($this->user)
        ];
    }
    ```

    ```php
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'secret' => 'abc',
            'title' => $this->title,
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'user' => new UserResource($this->user)
        ];

        if ($this->user_id == 1) {
            $data['secret'] = 'abc';
        }

        return $data;
    }
    ```

    ```php
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'secret' => $this->when($this->user_id == 1, ['abc']),
            'title' => $this->title,
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'user' => new UserResource($this->user)
        ];
    }
    ```

    ```php
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            $this->mergeWhen($this->user_id == 1, [
                'secret' => 'abc'
            ]),
            'title' => $this->title,
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'user' => new UserResource($this->user)
        ];
    }
    ```

## Unit tests

- Create unit test:

    ```bash
    $ php artisan make:test TopicResourceTest --unit
    ```

- In tests/Unit/TopicResource.php:

    ```php
    public function testReturnsCorrectData()
    {
        $resource = new \App\Http\Resources\TopicResource($topic = factory(\App\Topic::class)->create());
        dd($resource);
    }
    ```

    ```php
    public function testReturnsCorrectData()
    {/
        $resource = (new \App\Http\Resources\TopicResource($topic = factory(\App\Topic::class)->create()))->jsonSerialize();
        dd($resource);
    }
    ```

    ```php
    public function testReturnsCorrectData()
    {
        $resource = (new \App\Http\Resources\TopicResource($topic = factory(\App\Topic::class)->create()))->jsonSerialize();
        $this->assertArraySubset([
            'id' => $topic->id,
            'title' => $topic->title
        ], $resource);
    }
    ```