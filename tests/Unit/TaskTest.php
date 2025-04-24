<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    public function test_task_can_be_created()
    {
        $task = Task::factory()->create([
            'title' => 'Test Task',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
        ]);
    }

    public function test_task_can_be_read()
    {
        $task = Task::factory()->create();

        $found = Task::find($task->id);

        $this->assertEquals($task->title, $found->title);
    }

    public function test_task_can_be_updated()
    {
        $task = Task::factory()->create();

        $task->update(['title' => 'Updated Title']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_task_can_be_deleted()
    {
        $task = Task::factory()->create();

        $task->delete();

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_task_can_be_reordered()
    {
        $task1 = Task::factory()->create(['order' => 1]);
        $task2 = Task::factory()->create(['order' => 2]);

        $task1->update(['order' => 2]);
        $task2->update(['order' => 1]);

        $this->assertEquals(2, Task::find($task1->id)->order);
        $this->assertEquals(1, Task::find($task2->id)->order);
    }
}
