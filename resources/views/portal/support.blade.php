@extends('layouts.portal')

@section('title', 'Help & Support — FinCore Portal')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="{ openFaq: null }">

    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Help & Customer Support</h1>
        <p class="text-xs text-slate-500">Need help with your loan application or repayment? Contact our support desk or submit an inquiry.</p>
    </div>

    {{-- Contact Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase">Customer Support Line</span>
                <span class="font-bold text-slate-900 text-sm">+233 (0) 30 123 4567</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase">WhatsApp Instant Help</span>
                <a href="https://wa.me/233241234567" target="_blank" class="font-bold text-emerald-600 hover:underline text-sm">+233 (0) 24 123 4567</a>
            </div>
        </div>
    </div>

    {{-- FAQ Accordion --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Frequently Asked Questions</h3>

        <div class="space-y-2 text-xs">
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full text-left p-4 font-bold text-slate-900 flex justify-between items-center bg-slate-50/50">
                    <span>How do I pay off my loan using Paystack?</span>
                    <span x-text="openFaq === 1 ? '−' : '+'" class="font-extrabold text-blue-600 text-sm"></span>
                </button>
                <div x-show="openFaq === 1" class="p-4 border-t border-slate-100 text-slate-600 leading-relaxed bg-white">
                    Navigate to "My Loans", click "Pay via Paystack" on your active loan, enter your desired partial or full payment amount, and proceed through the secure Paystack checkout using Mobile Money or Bank Card.
                </div>
            </div>

            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full text-left p-4 font-bold text-slate-900 flex justify-between items-center bg-slate-50/50">
                    <span>How is interest calculated on my loan balance?</span>
                    <span x-text="openFaq === 2 ? '−' : '+'" class="font-extrabold text-blue-600 text-sm"></span>
                </button>
                <div x-show="openFaq === 2" class="p-4 border-t border-slate-100 text-slate-600 leading-relaxed bg-white">
                    Loans carry an initial interest rate upon creation. Unpaid balances remaining at calendar month-end incur a 30% monthly compound interest on the closing balance. Full transparency is provided in your Interest/Balance Timeline.
                </div>
            </div>

            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full text-left p-4 font-bold text-slate-900 flex justify-between items-center bg-slate-50/50">
                    <span>Can I download or print a payment receipt?</span>
                    <span x-text="openFaq === 3 ? '−' : '+'" class="font-extrabold text-blue-600 text-sm"></span>
                </button>
                <div x-show="openFaq === 3" class="p-4 border-t border-slate-100 text-slate-600 leading-relaxed bg-white">
                    Yes! Every successful payment generates an official digital receipt. Visit "Payments & Receipts" to view and print your receipts at any time.
                </div>
            </div>
        </div>
    </div>

    {{-- Inquiry Form --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Submit a Direct Inquiry</h3>

        <form action="{{ route('portal.support.inquiry') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Subject *</label>
                <input type="text" name="subject" required placeholder="e.g. Loan repayment clarification"
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Message Details *</label>
                <textarea name="message" rows="4" required placeholder="Type your message or inquiry here..."
                          class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-xs">
                    Send Inquiry
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
