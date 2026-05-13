<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class FlightResults extends Component
{
    public $origin = 'SFO';

    public $destination = 'LHR';

    public $date = 'Oct 24';

    public $passengers = 1;

    public function mount()
    {
        $this->origin = request('origin', $this->origin);
        $this->destination = request('destination', $this->destination);
        $this->date = request('date', $this->date);
    }

    public function getFlightsProperty()
    {
        return [
            (object) [
                'cheapest' => true,
                'airline_name' => 'British Airways',
                'airline_logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAXGhmcHjSwOr4GuM9hPxvEQrvOTDA3Rf85Uf89sidqTq3KcRBbufSfDgR6LJdh3RNhXeO0OtqngD6fQtnQ2gVb8gxLjwArtnZKfqbxZ4TBoZUg6W6cIyEphSCQ0EiDNt-Ysm2NtIzpKyuQjEs0Mt0K689LGObcARfbYcLl_PAVjChJdrGJp-6FU2SH7RdK3ksQSBiAily9nhSq980vT9DbjZSGI0sTs5F-7ki1IorBK1iAZq4WPEKwTcSerZcvvyDD2fFiPtetG74F',
                'flight_number' => 'Flight BA284',
                'price' => '542',
                'price_status' => 'Price is Low',
                'departure_time' => '11:35',
                'arrival_time' => '08:20',
                'origin_code' => 'SFO',
                'destination_code' => 'LHR +1',
                'duration' => '10h 45m',
                'stops' => 'Non-stop',
            ],
            (object) [
                'cheapest' => false,
                'airline_name' => 'Virgin Atlantic',
                'airline_logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB85FV6LOIGRF1rvenV7UtDvx5Jy5n7YRjos0Qf5tDUaFM6D_bm8N-4bGh2oMjIgbM-56Rp0YJAkKBDRJRU99OUhmvgHO23RTQDCZiAOeNcVsG7K01NamFsOcUZugao5atEp73Xmr---ZpUltxtPDg5MFYOA3J0peQYYEgK4AjTRb_Er6PBdgeR7tmP0GH0U_BH7M4azP2xtu_tjjyFxgJxS_tCD5YbaNlTomIZAkGGCZDhGzWj4565owt-umwVEnMVZNa7JPFffADS',
                'flight_number' => 'Flight VS20',
                'price' => '721',
                'price_status' => 'Standard price',
                'departure_time' => '17:50',
                'arrival_time' => '15:00',
                'origin_code' => 'SFO',
                'destination_code' => 'LHR +1',
                'duration' => '11h 10m',
                'stops' => 'Non-stop',
            ],
        ];
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        return view('livewire.flight-results');
    }
}
