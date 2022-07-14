<?php

namespace App\Http\Livewire;

use Livewire\Component;

// Child component — emits events up to parent.
// $this->emit('event-name', ...params) sends the event.
// Parent must declare $listeners = ['event-name' => 'methodName'].

class ChildItemList extends Component
{
    public array $items = [
        ['id' => 1, 'name' => 'Invoice #001', 'status' => 'paid'],
        ['id' => 2, 'name' => 'Invoice #002', 'status' => 'pending'],
        ['id' => 3, 'name' => 'Invoice #003', 'status' => 'overdue'],
    ];

    public function select(int $id): void
    {
        $item = collect($this->items)->firstWhere('id', $id);
        // Emit to parent (and any sibling listening)
        $this->emit('item-selected', $id, $item['name']);
    }

    public function delete(int $id): void
    {
        $this->items = collect($this->items)
            ->filter(fn($i) => $i['id'] !== $id)
            ->values()
            ->toArray();

        $this->emit('item-deleted', $id);
    }

    public function render()
    {
        return view('livewire.child-item-list');
    }
}
