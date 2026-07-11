<?php

namespace Database\Seeders;

use App\Models\ItsmTicket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ItsmTicketSeeder extends Seeder
{
    /**
     * Seed itsm_tickets, matching the "Server outage / VPN issue / Email
     * delay" incidents already referenced in the mock UI.
     */
    public function run(): void
    {
        $tickets = [
            ['ticket_number' => 'TCK-1001', 'subject' => 'Server outage', 'priority' => 'critical', 'status' => 'resolved', 'category' => 'Infrastructure', 'opened_at' => Carbon::now()->subHours(3), 'resolved_at' => Carbon::now()->subHours(1)],
            ['ticket_number' => 'TCK-1002', 'subject' => 'VPN issue', 'priority' => 'high', 'status' => 'in_progress', 'category' => 'Network', 'opened_at' => Carbon::now()->subHours(6)],
            ['ticket_number' => 'TCK-1003', 'subject' => 'Email delay', 'priority' => 'medium', 'status' => 'resolved', 'category' => 'Messaging', 'opened_at' => Carbon::now()->subDay(), 'resolved_at' => Carbon::now()->subDay()->addHours(2)],
            ['ticket_number' => 'TCK-1004', 'subject' => 'Laptop request - new hire', 'priority' => 'low', 'status' => 'open', 'category' => 'Hardware', 'opened_at' => Carbon::now()->subHours(20)],
            ['ticket_number' => 'TCK-1005', 'subject' => 'Password reset backlog', 'priority' => 'low', 'status' => 'open', 'category' => 'Access Management', 'opened_at' => Carbon::now()->subHours(10)],
            ['ticket_number' => 'TCK-1006', 'subject' => 'Database replication lag', 'priority' => 'critical', 'status' => 'open', 'category' => 'Infrastructure', 'opened_at' => Carbon::now()->subMinutes(45)],
        ];

        foreach ($tickets as $ticket) {
            ItsmTicket::create($ticket);
        }
    }
}
