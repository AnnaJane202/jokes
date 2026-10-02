<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class MassAssignmentTest extends TestCase
{
    public function test_mass_assignment_is_guarded(): void
    {
        $this->assertFalse(
            Model::isUnguarded(),
            'Model::unguard() включён — это снимает защиту от массового присваивания'
        );
    }
}
