<?php

namespace Botble\Course\Contracts;

use Botble\Course\CourseCollection;

interface Course
{
    public function registerSchema(CourseCollection $courses): void;
}
