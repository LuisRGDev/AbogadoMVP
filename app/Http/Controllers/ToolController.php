<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeveranceCalculationRequest;
use App\Services\SeveranceCalculator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ToolController extends Controller
{
    public function severance(SeveranceCalculator $calculator): View
    {
        return view('pages.tools.severance', [
            'result' => session('result'),
            'minimumWage' => $calculator->minimumWage('general'),
        ]);
    }

    public function calculateSeverance(SeveranceCalculationRequest $request, SeveranceCalculator $calculator): RedirectResponse
    {
        return redirect()
            ->to(route('tools.severance').'#resultado')
            ->withInput()
            ->with('result', $calculator->calculate($request->validated()));
    }
}
