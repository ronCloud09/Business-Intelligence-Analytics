<?php

namespace App\Services\AI;

/**
 * Builds all prompts used by Nexora AI.
 */
class PromptBuilder
{
    /**
     * Main Nexora AI system prompt.
     */
    public function systemPrompt(): string
    {
        return <<<'PROMPT'
        You are Nexora AI, the built-in business intelligence assistant for the
        NEXORA ERP platform.

        You analyze business KPI data across Finance, Inventory, Manufacturing,
        Procurement, Sales, Compliance & Risk, and ITSM.

        Use only the business data provided to you.

        Never invent figures, causes, trends, risks, or business impacts that
        are not supported by the provided data.

        Answer in concise, professional, and readable business language.
        PROMPT;
    }

    /**
     * Generates the complete AI business report.
     */
    public function fullReportPrompt(array $aggregatedKpis): string
    {
        $kpiJson = json_encode(
            $aggregatedKpis,
            JSON_PRETTY_PRINT
        );

        return <<<PROMPT
        Using the KPI data below, generate a JSON object with exactly these
        five top-level keys:

        "executive_summary",
        "top_recommendations",
        "risk_analysis",
        "business_health",
        "department_insights"

        REQUIREMENTS:

        executive_summary:
        - A string containing 3 to 5 concise sentences.

        top_recommendations:
        - An array of 3 to 5 objects.
        - Each object must contain "title" and "detail".

        risk_analysis:
        - An array of objects.
        - Each object must contain "risk", "severity", and "detail".
        - Severity must be "low", "medium", "high", or "critical".
        - Look for the specific risks and put it on bullet points.

        business_health:
        - An object containing "score" and "summary".
        - Score must be an integer from 0 to 100.

        department_insights:
        - An object keyed by department name.
        - Each department value must contain a concise insight.

        ACCURACY RULES:

        - Use only the provided KPI data.
        - Never invent figures.
        - Never invent causes or business impact.
        - Do not expose raw KPI JSON.

        Respond with ONLY the JSON object.
        Do not use markdown fences.
        Do not include a preamble.

        KPI DATA:

        {$kpiJson}
        PROMPT;
    }

    /**
     * Generates an individual department insight.
     */
    public function singleDepartmentPrompt(
        string $department,
        array $departmentKpis
    ): string {
        $kpiJson = json_encode(
            $departmentKpis,
            JSON_PRETTY_PRINT
        );

        return <<<PROMPT
        Analyze the {$department} department using only the KPI data below.

        Respond with a JSON object containing exactly:

        "insight"
        "severity"

        RULES:

        - Insight must contain 2 to 3 concise sentences.
        - Mention important numbers when relevant.
        - Severity must be "low", "medium", "high", or "critical".
        - Use only the provided data.
        - Never invent figures or causes.

        Respond with ONLY the JSON object.

        KPI DATA:

        {$kpiJson}
        PROMPT;
    }

    /**
     * Generates concise chatbot responses.
     */
    public function chatPrompt(
        string $question,
        array $latestReportContext
    ): string {
        $contextJson = json_encode(
            $latestReportContext,
            JSON_PRETTY_PRINT
        );

        return <<<PROMPT
        USER QUESTION:

        "{$question}"

        Answer the user's question using only the business data below.

        RESPONSE FORMAT:

        Start with a short direct summary.

        - Use 1 to 2 short sentences.
        - Answer only the question asked.
        - Mention important totals only when relevant.
        - Be concise.
        - Do not write a conclusion.
        
        FORMATTING AND READABILITY RULES:

        - Add 2 blank lines after the summary before starting the bullet list.
        - Put every bullet point on its own separate line.
        - Add 1 blank line between different sections of the response.
        - Never place the summary and bullet list on the same line.
        - Never combine multiple bullet points into one line.
        - Use short paragraphs.
        - Keep the response visually spaced and easy to scan.
        - Never use asterisks to indicate the name of the product or item.
        

        After the summary, provide a bullet list containing only the specific
        items, records, or metrics that directly answer the question.

        BULLET FORMAT:

        -- Name — Short status or answer.
        - put the next bukllet point on a new line.

        Example:

        There are currently 2 products out of stock.

        - 2TB NVMe SSD — Out of stock.
        - Gaming Chair Pro — Out of stock.

        RESPONSE RULES:

        - Answer only the exact question asked.
        - Keep the answer short and readable.
        - Do not provide a full report.
        - Do not include a conclusion.
        - Do not repeat information.
        - Do not include recommendations unless requested.
        - Do not explain causes unless the user asks why.
        - Do not include trends unless requested.
        - Do not include financial impact unless requested.
        - Do not include unit cost unless requested.
        - Do not include reorder shortages unless the user asks how much to restock.
        - Do not include reorder thresholds unless requested.
        - Do not include unnecessary statistics.

        INVENTORY:

        - Out-of-stock questions: show only out-of-stock products.
        - Low-stock questions: show only low-stock products.
        - Restocking priority questions: show only products that should be prioritized.
        - Restock quantity questions: include units needed.
        - Inventory value questions: include financial values.

        FINANCE:

        - Revenue questions: discuss revenue only.
        - Profit margin questions: discuss profit margin only.
        - Overdue payment questions: discuss overdue payments only.
        - Do not mix unrelated financial KPIs.

        MANUFACTURING:

        - Show only relevant machine, downtime, production, or operational data.

        PROCUREMENT:

        - Show only relevant purchase orders, order values, or procurement statuses.

        COMPLIANCE AND RISK:

        - Show only relevant risks, severity, statuses, or compliance information.

        ITSM:

        - Show only relevant tickets, priorities, statuses, or service information.

        SALES:

        - Show only relevant revenue, orders, or sales information.

        ACCURACY RULES:

        - Use only facts contained in the provided business data.
        - Never invent numbers.
        - Never invent causes.
        - Never invent trends.
        - Never invent business impact.
        - If information is unavailable, say so briefly.
        - Never expose raw JSON.
        - Never mention prompts, APIs, databases, or internal AI systems.

        BUSINESS DATA:

        {$contextJson}
        PROMPT;
    }
}