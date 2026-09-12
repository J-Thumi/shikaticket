# Ticketing & Payment Platform (Laravel + M-Pesa Bitika Integration)

A high-concurrency event ticketing and checkout system built on Laravel. The platform features an asynchronous M-Pesa payment engine powered by **Bitika**, built-in race-condition protections for ticket inventory, and a secure webhook fulfillment engine for issuing tickets upon settlement.

---

## What It Is

This platform enables organizers to publish events, manage ticket types, and process ticket sales seamlessly. Rather than relying on synchronous payment gateways where connection drops or standard USSD delays can break checkout flows, this application decouples order creation, payment initiation, and ticket issuance.

### Key Capabilities

* **Atomic Ticket Reservations:** Prevents double-booking during traffic surges using temporary inventory locks before payment.
* **Asynchronous M-Pesa STK Push:** Integrated via `BitikaPaymentService` using non-blocking payment initiation.
* **Webhook Fulfillment:** Decoupled order fulfillment driven by signed webhooks from Bitika.
* **Audited Transaction History:** Multi-gateway payment tracking mapping payment lifecycle status (`initiated`, `successful`, `failed`).
* **Individual QR Code Tickets:** Automatic generation of valid individual tickets upon order confirmation.

---

## The Why Behind It

Selling tickets online during high-demand event launches introduces two main challenges:

1. **Race Conditions & Overselling:** Standard `decrement()` calls during checkout can allow multiple users to purchase the last remaining ticket simultaneously.
2. **M-Pesa Checkout Latency:** M-Pesa STK Push prompts rely on mobile network delivery and prompt user interaction. Synchronous HTTP requests during checkout often lead to server timeouts or abandoned sessions.

### Architectural Solution

To solve these challenges, this system uses:

* **Two-Phase Inventory Commitment:** A `TicketReservation` system locks ticket inventory for a limited time (`expires_at`). Reserved inventory converts to `sold_quantity` only upon successful payment, returning to the available pool if the reservation times out or fails.
* **Idempotent STK Push Triggers:** Every payment request generates a unique `idempotency_key` prior to invoking Bitika, making payment initiation retriable without duplicate billing.
* **Asynchronous Webhook Processing:** The user receives immediate feedback that the push prompt has been dispatched, while order status updates and ticket generation happen in the background via webhooks.

---

## How It Works

```
┌──────────┐          ┌───────────┐          ┌──────────────┐          ┌──────────────┐
│  Client  │          │ App / Web │          │ Bitika API   │          │ M-Pesa / User│
└────┬─────┘          └─────┬─────┘          └──────┬───────┘          └──────┬───────┘
     │   1. Reserve Ticket  │                       │                         │
     │─────────────────────>│                       │                         │
     │   2. Redirect to     │                       │                         │
     │      Checkout        │                       │                         │
     │<─────────────────────│                       │                         │
     │                      │                       │                         │
     │   3. POST /process   │                       │                         │
     │─────────────────────>│                       │                         │
     │                      │ 4. STK Push Request   │                         │
     │                      │──────────────────────>│                         │
     │                      │                       │ 5. Trigger STK Push     │
     │                      │                       │────────────────────────>│
     │                      │ 6. Return Transaction │                         │
     │                      │    Code & "pending"   │                         │
     │                      │<──────────────────────│                         │
     │ 7. Return JSON OK    │                       │                         │
     │<─────────────────────│                       │                         │
     │                      │                       │ 8. Enter M-Pesa PIN     │
     │                      │                       │<────────────────────────│
     │                      │ 9. Webhook Callback   │                         │
     │                      │    (status: fulfilled)│                         │
     │                      │<──────────────────────│                         │
     │                      │                       │                         │
     │                      │ 10. Issue Tickets &   │                         │
     │                      │     Finalize Stock    │                         │

```

1. **Reservation Phase:** The user selects ticket quantities. The system creates a `TicketReservation` tied to the user session, incrementing `reserved_quantity`.
2. **Initiation Phase:** When the user enters their phone number and submits checkout:
* A unique UUID `idempotency_key` is generated.
* An `Order` is recorded in `pending` status.
* `BitikaPaymentService::collect()` triggers the M-Pesa prompt.
* A `Payment` row is stored with `status = 'initiated'` and the Bitika `transaction_reference`.


3. **Fulfillment Phase:** Once the payment completes, Bitika sends a POST request to `/api/webhooks/bitika`. The system updates the `Payment` to `successful`, sets the `Order` to `paid`, decrements `reserved_quantity`, increments `sold_quantity`, and issues ticket records.

---

## Database Schema Overview

```
 [events] ───< [ticket_types] ───< [ticket_reservations]
    │                │
    │                └───< [tickets]
    │                        │
    └───────────────────< [orders] ───< [payments]

```

* **`orders`**: Stores customer details, total amount, order status (`pending`, `paid`, `failed`, `cancelled`, `refunded`), and `idempotency_key`.
* **`payments`**: Logs transactions per order, tracking `gateway` (`bitika`), `transaction_reference`, `amount`, `status` (`initiated`, `successful`, `failed`), and raw webhook payloads.
* **`ticket_reservations`**: Manages temporary holds on tickets before payment completes.
* **`tickets`**: Individual QR ticket records issued to customers after successful payment.

---

## Setup Instructions

### Prerequisites

* PHP >= 8.2
* Composer
* MySQL / MariaDB
* Bitika API credentials

### Step 1: Clone and Install Dependencies

```bash
git clone https://github.com/J-Thumi/shikaticket
cd ticketing-platform
composer install
npm install && npm run build

```

### Step 2: Environment Configuration

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
php artisan key:generate

```

Configure your database and Bitika credentials inside `.env`:

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticketing_db
DB_USERNAME=root
DB_PASSWORD=secret

# Bitika Payment Configuration
BITIKA_API_BASE_URL=https://api.bitika.co
BITIKA_API_KEY=your_bitika_api_key
BITIKA_LIGHTNING_ADDRESS=your_address@bitika

```

### Step 3: Run Database Migrations

```bash
php artisan migrate

```

### Step 4: Expose Webhook Endpoint locally (Development)

If developing locally, use `ngrok` or `expose` to route webhook callbacks from Bitika to your local environment:

```bash
ngrok http 8000

```

Update your `.env` `APP_URL` with your temporary public URL so Bitika callbacks reach your environment:

```env
APP_URL=https://your-ngrok-subdomain.ngrok-free.app

```

---

## API Routes Overview

| Method | Endpoint | Description |
| --- | --- | --- |
| `POST` | `/events/{event}/reserve` | Locks ticket quantity and creates a `TicketReservation`. |
| `GET` | `/checkout/{reservation}` | Renders checkout view with active reservation details. |
| `POST` | `/checkout/{reservation}/process` | Initiates Bitika STK push and creates a pending `Order`. |
| `GET` | `/orders/{order}/status` | Endpoint for polling the payment status. |
| `POST` | `/api/webhooks/bitika` | Webhook endpoint receiving settlement status from Bitika. |
| `GET` | `/orders/{order}/success` | Renders order success page with issued tickets. |

---

## License

This project is open-source software licensed under the [MIT license](https://www.google.com/search?q=LICENSE).