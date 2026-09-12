<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TicketingDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Super Admin / Platform User
        $admin = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'System Administrator',
            'email' => 'admin@passpulse.test',
            'phone' => '254700000000',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 2. Create Event Staff / Check-in Usher
        $staff = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Gate Usher',
            'email' => 'staff@passpulse.test',
            'phone' => '254711111111',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // 3. Create Organizer 1
        $organizerUser1 = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Nairobi Tech Hub',
            'email' => 'events@techhub.co.ke',
            'phone' => '254722000111',
            'password' => Hash::make('password'),
            'role' => 'organizer',
        ]);

        $organizer1 = Organizer::create([
            'user_id' => $organizerUser1->id,
            'name' => 'Nairobi Tech Hub',
            'slug' => 'nairobi-tech-hub',
            'logo_url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=300',
            'payout_details' => [
                'type' => 'mpesa_paybill',
                'paybill' => '400200',
                'account' => 'TECHHUB',
            ],
        ]);

        // 4. Create Organizer 2
        $organizerUser2 = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Vibe Tribe Entertainment',
            'email' => 'info@vibetribe.live',
            'phone' => '254733999000',
            'password' => Hash::make('password'),
            'role' => 'organizer',
        ]);

        $organizer2 = Organizer::create([
            'user_id' => $organizerUser2->id,
            'name' => 'Vibe Tribe Entertainment',
            'slug' => 'vibe-tribe',
            'logo_url' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=300',
            'payout_details' => [
                'type' => 'bank_transfer',
                'bank' => 'Equity Bank',
                'account_number' => '011029384756',
            ],
        ]);

        // 5. Create Events for Organizer 1
        $event1 = Event::create([
            'organizer_id' => $organizer1->id,
            'title' => 'DevFest Kenya 2026',
            'slug' => 'devfest-kenya-2026',
            'description' => "Join over 1,000 software developers, architects, and tech enthusiasts for East Africa's premier technology conference. Keynotes on AI, Cloud Infrastructure, and Scalable Web Architectures.",
            'venue_name' => 'Sarit Expo Centre',
            'venue_address' => 'Pio Gama Pinto Rd, Westlands, Nairobi',
            'start_date' => now()->addDays(14)->setHour(8)->setMinute(30),
            'end_date' => now()->addDays(14)->setHour(18)->setMinute(0),
            'banner_url' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=1200',
            'status' => 'published',
            'settings' => [
                'allow_refunds' => false,
                'custom_questions' => ['T-shirt Size', 'Dietary Preference'],
            ],
        ]);

        // Ticket Types for Event 1
        $earlyBird = TicketType::create([
            'event_id' => $event1->id,
            'name' => 'Early Bird Access',
            'price' => 1500.00,
            'total_quantity' => 100,
            'reserved_quantity' => 0,
            'sold_quantity' => 15,
            'min_per_order' => 1,
            'max_per_order' => 5,
            'is_active' => true,
        ]);

        $regularDev = TicketType::create([
            'event_id' => $event1->id,
            'name' => 'Regular Pass',
            'price' => 3000.00,
            'total_quantity' => 300,
            'reserved_quantity' => 0,
            'sold_quantity' => 45,
            'min_per_order' => 1,
            'max_per_order' => 10,
            'is_active' => true,
        ]);

        $vipDev = TicketType::create([
            'event_id' => $event1->id,
            'name' => 'VIP Speaker & Networking Pass',
            'price' => 8000.00,
            'total_quantity' => 50,
            'reserved_quantity' => 0,
            'sold_quantity' => 10,
            'min_per_order' => 1,
            'max_per_order' => 3,
            'is_active' => true,
        ]);

        // 6. Create Event for Organizer 2
        $event2 = Event::create([
            'organizer_id' => $organizer2->id,
            'title' => 'Sundowner Music & Food Festival',
            'slug' => 'sundowner-fest-2026',
            'description' => 'Experience an unforgettable evening of live Afrobeats, artisanal food stalls, craft cocktails, and sunset views with live DJ performances.',
            'venue_name' => 'Carnivore Grounds',
            'venue_address' => 'Langata Road, Nairobi',
            'start_date' => now()->addDays(21)->setHour(14)->setMinute(0),
            'end_date' => now()->addDays(21)->setHour(23)->setMinute(59),
            'banner_url' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=1200',
            'status' => 'published',
            'settings' => [
                'age_limit' => '18+',
            ],
        ]);

        TicketType::create([
            'event_id' => $event2->id,
            'name' => 'Advance Ticket',
            'price' => 2000.00,
            'total_quantity' => 500,
            'reserved_quantity' => 0,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 10,
            'is_active' => true,
        ]);

        TicketType::create([
            'event_id' => $event2->id,
            'name' => 'VIP Lounge Ticket',
            'price' => 5000.00,
            'total_quantity' => 100,
            'reserved_quantity' => 0,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 4,
            'is_active' => true,
        ]);

        // 7. Seed Sample Confirmed Customer Order & Tickets
        $customer = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Jane Wambui',
            'email' => 'jane.wambui@example.com',
            'phone' => '254712345678',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'event_id' => $event1->id,
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'total_amount' => 3000.00,
            'currency' => 'KES',
            'status' => 'paid',
            'idempotency_key' => (string) Str::uuid(),
        ]);

        $order->payment()->create([
            'gateway' => 'mpesa',
            'transaction_reference' => 'QRE9283741',
            'amount' => 3000.00,
            'status' => 'successful',
            'payload' => ['MpesaReceiptNumber' => 'QRE9283741', 'ResultCode' => 0],
        ]);

        $ticketCode = (string) Str::uuid();

        Ticket::create([
            'order_id' => $order->id,
            'ticket_type_id' => $regularDev->id,
            'ticket_code' => $ticketCode,
            'qr_code_hash' => hash('sha256', $ticketCode),
            'status' => 'valid',
        ]);
    }
}