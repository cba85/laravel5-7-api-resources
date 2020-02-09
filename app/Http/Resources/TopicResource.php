<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /*
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
        */

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
}
