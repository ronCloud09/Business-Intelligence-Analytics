<?php

namespace Database\Seeders;

use App\Models\ComplianceRisk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ComplianceRiskSeeder extends Seeder
{
    /**
     * Seed compliance_risks, matching the SOC 2 / ISO 27001 / GDPR
     * checklist already shown in the Department Analytics mock UI.
     */
    public function run(): void
    {
        $risks = [
            ['title' => 'SOC 2 Type II Audit', 'standard' => 'SOC 2', 'severity' => 'medium', 'status' => 'closed', 'identified_date' => Carbon::parse('2026-03-01'), 'due_date' => Carbon::parse('2026-03-15')],
            ['title' => 'ISO 27001 Recertification', 'standard' => 'ISO 27001', 'severity' => 'medium', 'status' => 'closed', 'identified_date' => Carbon::parse('2026-01-05'), 'due_date' => Carbon::parse('2026-01-20')],
            ['title' => 'GDPR Data Retention Review', 'standard' => 'GDPR', 'severity' => 'high', 'status' => 'in_review', 'description' => 'Data retention policy needs updating for EU customer records.', 'identified_date' => Carbon::today()->subDays(30), 'due_date' => Carbon::parse('2026-06-30')],
            ['title' => 'Vendor Access Review Overdue', 'standard' => null, 'severity' => 'high', 'status' => 'open', 'description' => 'Third-party vendor access permissions have not been reviewed this quarter.', 'identified_date' => Carbon::today()->subDays(12)],
            ['title' => 'Unpatched Server Vulnerability', 'standard' => null, 'severity' => 'critical', 'status' => 'open', 'description' => 'Critical CVE flagged on a production database server awaiting patch window.', 'identified_date' => Carbon::today()->subDays(3)],
        ];

        foreach ($risks as $risk) {
            ComplianceRisk::create($risk);
        }
    }
}
