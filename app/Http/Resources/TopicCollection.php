<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TopicCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        //dd($this->collection);
        /*
        return [
            'id' => $this->id,
            'title' => $this->title
        ];
        */
        /*
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->collection->count()
            ]
        ];
        */
        return [
            'data' => TopicResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count()
            ]
        ];
    }
}
