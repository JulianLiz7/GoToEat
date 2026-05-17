<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Domains\Restaurant\Models\Restaurant;

class FinanceLayout extends Component
{
    public function __construct(
        public ?Restaurant $restaurant = null,
    ) {}

    public function render(): View
    {
        return view('layouts.finance');
    }
}
