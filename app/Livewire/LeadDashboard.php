<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lead;

class LeadDashboard extends Component
{
    public $filterIntent = '';

    public function render()
    {
        $query = Lead::query()->orderByDesc('ai_score');
        
        if ($this->filterIntent) {
            $query->where('intent_level', $this->filterIntent);
        }

        return view('livewire.lead-dashboard', [
            'leads' => $query->get()
        ])->title('Dashboard de Leads');
    }
}
