<?php

namespace App\Services\AI;

/**
 * Builds the reusable system and task prompts for every AI-powered
 * feature. Centralizing these here means:
 *   - the chatbot persona is defined once, not re-typed per request
 *   - the "one request produces five report sections" rule lives in
 *     one place instead of being duplicated across Jobs
 */
class PromptBuilder
{
    /**
     * The chatbot/report system prompt. Nexora AI already knows who it
     * is — callers never need to restate this.
     */
    public function systemPrompt(): string
    {
        return <<<'PROMPT'
        You are Nexora AI, the built-in business intelligence assistant for the
        NEXORA ERP platform. You analyze summarized KPI data across Finance,
        Inventory, Manufacturing, Procurement, Sales, Compliance & Risk, and
        ITSM. You never receive raw database rows — only pre-aggregated KPI
        summaries. Be concise, concrete, and reference specific numbers you
        were given. Never invent figures that were not provided to you.
        PROMPT;
    }

    /**
     * The single combined prompt used to generate Executive Summary, Top
     * Recommendations, Risk Analysis, Business Health, and Department
     * Insights in ONE request. Must instruct the model to return strict
     * JSON so AIManager can split it into separate ai_reports rows.
     *
     * @param  array<string, mixed>  $aggregatedKpis  Output of DashboardAggregator::collectAll()
     */
    public function fullReportPrompt(array $aggregatedKpis): string
    {
        $kpiJson = json_encode($aggregatedKpis, JSON_PRETTY_PRINT);

        return <<<PROMPT
        Using the KPI data below, generate a single JSON object with exactly
        these five top-level keys: "executive_summary", "top_recommendations",
        "risk_analysis", "business_health", "department_insights".

        Requirements for each key:
        - executive_summary: a string, 3-5 sentences, plain language overview.
        - top_recommendations: an array of 3-5 objects, each with "title" and
          "detail" string fields.
        - risk_analysis: an array of objects, each with "risk", "severity"
          (one of "low", "medium", "high", "critical"), and "detail".
        - business_health: an object with "score" (0-100 integer) and
          "summary" (string explaining the score).
        - department_insights: an object keyed by department name (matching
          the keys in the KPI data below), each value a short string insight
          specific to that department's numbers.

        Respond with ONLY the JSON object. No markdown fences, no preamble.

        KPI DATA:
        {$kpiJson}
        PROMPT;
    }

    /**
     * Prompt used for a single event-driven insight (Package 7) — e.g. one
     * department crossed a threshold and only that department's insight
     * needs regenerating, not the full report.
     *
     * @param  array<string, mixed>  $departmentKpis
     */
    public function singleDepartmentPrompt(string $department, array $departmentKpis): string
    {
        $kpiJson = json_encode($departmentKpis, JSON_PRETTY_PRINT);

        return <<<PROMPT
        The {$department} department just crossed an alert threshold. Using
        only the KPI data below, respond with a JSON object with exactly two
        keys: "insight" (a 2-3 sentence string explaining what changed and
        why it matters) and "severity" (one of "low", "medium", "high",
        "critical"). Respond with ONLY the JSON object.

        KPI DATA:
        {$kpiJson}
        PROMPT;
    }

    /**
     * Prompt used by ChatService (Package 3) when a chatbot question
     * genuinely needs reasoning rather than a direct database lookup.
     *
     * @param  string  $question
     * @param  array<string, mixed>  $latestReportContext  Current AIReport contents, keyed by type
     */
    public function chatPrompt(string $question, array $latestReportContext): string
    {
        $contextJson = json_encode($latestReportContext, JSON_PRETTY_PRINT);

        return <<<PROMPT
        A user asked: "{$question}"

        Answer using the latest AI report context below. Be direct and cite
        specific numbers where relevant. If the context doesn't contain
        enough information to answer confidently, say so plainly.

        LATEST REPORT CONTEXT:
        {$contextJson}
        PROMPT;
    }
}
