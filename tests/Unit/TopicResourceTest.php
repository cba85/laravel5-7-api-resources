<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicResourceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testReturnsCorrectData()
    {
        /*
        $resource = new \App\Http\Resources\TopicResource($topic = factory(\App\Topic::class)->create());
        dd($resource->toArray(request()));
        */
        $resource = (new \App\Http\Resources\TopicResource($topic = factory(\App\Topic::class)->create()))->jsonSerialize();
        //dd($resource);
        $this->assertArraySubset([
            'id' => $topic->id,
            'title' => $topic->title
        ], $resource);
    }

}
