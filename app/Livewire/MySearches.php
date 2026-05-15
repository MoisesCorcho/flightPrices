<?php

namespace App\Livewire;

use App\Models\FlightSearch;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MySearches extends Component
{
    public $editingId = null;

    public $newTargetPrice = '';

    public function toggleActive($searchId)
    {
        $search = FlightSearch::query()->findOrFail($searchId);

        if (! $search->is_active) {
            // Deactivate all others first
            FlightSearch::query()
                ->where('id', '!=', $searchId)
                ->update(['is_active' => false]);

            $search->update(['is_active' => true]);
        } else {
            // If it was active and clicked again, we just deactivate it
            $search->update(['is_active' => false]);
        }
    }

    public function editTargetPrice($searchId)
    {
        $this->editingId = $searchId;
        $search = FlightSearch::where('user_id', Auth::id())->findOrFail($searchId);
        $this->newTargetPrice = $search->target_price;
    }

    public function saveTargetPrice()
    {
        $this->validate([
            'newTargetPrice' => 'required|numeric|min:0',
        ]);

        FlightSearch::where('user_id', Auth::id())
            ->where('id', $this->editingId)
            ->update(['target_price' => $this->newTargetPrice]);

        $this->editingId = null;
        $this->newTargetPrice = '';
    }

    public function cancelEdit()
    {
        $this->editingId = null;
        $this->newTargetPrice = '';
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        $searches = FlightSearch::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.my-searches', [
            'searches' => $searches,
        ]);
    }
}
