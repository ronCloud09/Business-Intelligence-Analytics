<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AIInsightsController extends Controller
{
    /**
     * Show the AI Insights Center.
     *
     * This currently renders the existing static view unchanged. From
     * Package 5 onward this method will inject the current AIReport
     * (Executive Summary, Recommendations, Risk Analysis) read from the
     * database instead of from Gemini directly.
     */
    public function index(): View
    {
        return view('ai-insights');
    }
}
