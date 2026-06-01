<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Servis — {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 320px;
            margin: 0 auto;
            padding: 16px 12px;
            background: #fff;
            color: #000;
        }
        .center   { text-align: center; }
        .bold     { font-weight: bold; }
        .sep-solid { border-top: 2px solid #000; margin: 8px 0; }
        .sep-dash  { border-top: 1px dashed #555; margin: 6px 0; }
        .row      { display: flex; justify-content: space-between; margin: 3px 0; }
        .title    { font-size: 20px; font-weight: bold; letter-spacing: 1px; }
        .total-row { font-size: 15px; font-weight: bold; }
        .item-name { font-size: 11px; }
        .small    { font-size: 10px; color: #555; }
        .print-btn-area { margin-top: 24px; text-align: center; }
        @media print {
            .no-print { display: none !important; }
            body { width: 100%; }
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="center">
        <p class="title">⚙ MyUOS</p>
        <p class="bold">Bengkel Servis Kendaraan</p>
        <p class="small">Jl. Raya Servis No. 1 | Telp: (021) 1234-5678</p>
    </div>
    <div class="sep-solid"></div>

    {{-- Order info --}}
    <div class="row"><span class="bold">No. Order:</span><span>{{ $order->order_number }}</span></div>
    <div class="row">
        <span class="bold">Tanggal:</span>
        <span>{{ $order->completed_at?->format('d/m/Y H:i') ?? $order->created_at->format('d/m/Y H:i') }}</span>
    </div>
    @if($order->relationLoaded('user') || $order->user)
    <div class="row"><span class="bold">Kasir:</span><span>{{ $order->user->name ?? '-' }}</span></div>
    @endif
    <div class="sep-dash"></div>

    {{-- Pelanggan --}}
    <p class="bold">Pelanggan:</p>
    <p>{{ $order->customer_name }}</p>
    <p>{{ $order->vehicle_type }} &mdash; <span class="bold">{{ $order->plate_number }}</span></p>
    @if($order->complaint)
    <p class="small" style="margin-top:3px;">Keluhan: {{ $order->complaint }}</p>
    @endif
    <div class="sep-dash"></div>

    {{-- Barang --}}
    <p class="bold">Rincian Barang:</p>
    <div style="margin-top:4px;">
        @forelse($order->items as $item)
        <div class="row item-name">
            <span>{{ $item->item_name }}</span>
        </div>
        <div class="row small">
            <span>&nbsp;&nbsp;{{ $item->quantity }} x Rp {{ number_format($item->price_at_time, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
        @empty
        <p class="small">— Tidak ada barang —</p>
        @endforelse
    </div>
    <div class="sep-dash"></div>

    {{-- Totals --}}
    <div class="row"><span>Total Barang:</span><span>Rp {{ number_format($order->total_items_cost, 0, ',', '.') }}</span></div>
    <div class="row"><span>Biaya Jasa:</span><span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span></div>
    <div class="sep-solid"></div>
    <div class="row total-row">
        <span>TOTAL BAYAR:</span>
        <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
    </div>
    <div class="sep-solid"></div>

    {{-- Footer --}}
    <div class="center" style="margin-top:10px;">
        <p>Terima kasih atas kepercayaan Anda!</p>
        <p class="small" style="margin-top:4px;">✓ Garansi servis 3 hari</p>
        <p class="small">Simpan struk ini sebagai bukti servis</p>
    </div>

    {{-- Print buttons (tidak tercetak) --}}
    <div class="no-print print-btn-area">
        <button onclick="window.print()"
                style="padding:9px 24px; background:#f97316; color:white; border:none; border-radius:8px; cursor:pointer; font-size:14px; font-weight:bold;">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()"
                style="padding:9px 20px; background:#71717a; color:white; border:none; border-radius:8px; cursor:pointer; font-size:14px; margin-left:10px;">
            Tutup
        </button>
    </div>
</body>
</html>