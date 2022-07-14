<?php

namespace App\Http\Livewire;

use Livewire\Component;

// Parent-child Livewire event pattern.
// Child emits, parent listens via $listeners.
// In React: prop callbacks (onSave, onChange) passed down.
// In Livewire: $emit() up + $listeners in parent. No prop drilling.
//
// Also: $emitTo('component-name', 'event') for sibling communication.
// And: $emitSelf('event') for emitting to yourself.

class ParentComponent extends Component
{
    public array $log = [];
    public ?int $selectedItemId = null;
    public string $selectedItemName = '';

    protected $listeners = [
        'item-selected' => 'onItemSelected',
        'item-deleted'  => 'onItemDeleted',
    ];

    public function onItemSelected(int $id, string $name): void
    {
        $this->selectedItemId = $id;
        $this->selectedItemName = $name;
        $this->log[] = "Item #{$id} selected: {$name}";
    }

    public function onItemDeleted(int $id): void
    {
        $this->log[] = "Item #{$id} deleted";
        if ($this->selectedItemId === $id) {
            $this->selectedItemId = null;
            $this->selectedItemName = '';
        }
    }

    public function render()
    {
        return view('livewire.parent-component');
    }
}
