@extends('layouts.organizer')

@section('content')

<div class="space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">

        <div>
            <p class="text-xs font-extrabold uppercase tracking-widest text-accent">
                Marketing
            </p>

            <h1 class="font-display text-3xl font-extrabold mt-1">
                Marketers
            </h1>

            <p class="text-sm text-muted mt-1">
                Manage your event promoters and track their ticket sales.
            </p>
        </div>

        <a
            href="{{ route('organizer.marketers.create') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-accent text-white text-sm font-bold hover:bg-accent-dark"
        >
            + Add Marketer
        </a>

    </div>


    <div class="bg-white border border-line rounded-3xl overflow-hidden shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-soft border-b border-line">

                    <tr class="text-left text-[10px] uppercase tracking-wider text-muted">

                        <th class="px-6 py-4 font-extrabold">
                            Marketer
                        </th>

                        <th class="px-6 py-4 font-extrabold">
                            Referral Code
                        </th>

                        <th class="px-6 py-4 font-extrabold">
                            Orders
                        </th>

                        <th class="px-6 py-4 font-extrabold">
                            Status
                        </th>

                        <th class="px-6 py-4">
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-line">

                    @forelse($marketers as $marketer)

                        <tr class="hover:bg-soft/50">

                            <td class="px-6 py-4">

                                <p class="font-bold text-ink">
                                    {{ $marketer->name }}
                                </p>

                                <p class="text-xs text-muted">
                                    {{ $marketer->email ?: $marketer->phone }}
                                </p>

                            </td>

                            <td class="px-6 py-4">

                                <code class="px-2.5 py-1 rounded-lg bg-soft text-xs font-bold">
                                    {{ $marketer->referral_code }}
                                </code>

                            </td>

                            <td class="px-6 py-4 font-bold">
                                {{ $marketer->orders_count }}
                            </td>

                            <td class="px-6 py-4">

                                @if($marketer->is_active)

                                    <span class="text-emerald-600 text-xs font-bold">
                                        ● Active
                                    </span>

                                @else

                                    <span class="text-muted text-xs font-bold">
                                        ● Inactive
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('organizer.marketers.show', $marketer) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-line bg-white px-3 py-2 text-xs font-bold text-ink transition hover:border-gray-300 hover:bg-gray-50"
                                        title="View marketer"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-3.5 w-3.5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.047.14.047.286 0 .428C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                        View
                                    </a>

                                    {{-- Manage --}}
                                    <a
                                        href="{{ route('organizer.marketers.edit', $marketer) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-ink px-3 py-2 text-xs font-bold text-white transition hover:bg-gray-800"
                                        title="Manage marketer"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-3.5 w-3.5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.065 2.573c.94 1.543-.827 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.065c-1.543.94-3.31-.827-2.37-2.37a1.724 1.724 0 00-1.065-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.065-2.572c-.94-1.544.827-3.31 2.37-2.37.996.608 2.296.07 2.573-1.066z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                        Edit
                                    </a>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-16 text-center"
                            >
                                <p class="font-bold">
                                    No marketers yet
                                </p>

                                <p class="text-xs text-muted mt-1">
                                    Add your first marketer to start
                                    tracking referred ticket sales.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection