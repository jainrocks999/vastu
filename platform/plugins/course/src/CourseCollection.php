<?php

namespace Botble\Course;

use Illuminate\Contracts\Support\Arrayable;

class CourseCollection implements Arrayable
{
    protected array $items = [];

    public function push(CourseItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function toArray(): array
    {
        return $this->items;
    }
}
