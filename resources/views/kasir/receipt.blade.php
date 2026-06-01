<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Servis - {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 12px; width: 300px; margin: 0 auto; padding: 10px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .separator { border-top: 1px dashed #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; margin: 2px 0; }
        .title { font-size: 18px; font-weight: bold; }
        .total-row { font-size: 14px; font-weight: bold; }
        @media print {
            .no-print { display: none; }
            body { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="center">
        <p class="title">🔧 MyUOS</p>
        <p>Bengkel Servis Kendaraan</p>
        <p>Telp: (021) 1234-5678</p>
    </div>
    <div class="separator"></div>
    <p><span class="bold">No. Order:</span> {{ $order->order_number }}</p>
    <p><span class="bold">Tanggal:</span> {{ $order->completed_at->format('d/m/Y H:i') }}</p>
    <p><span class="bold">Kasir:</span> {{ $order->user->name }}</p>
    <div class="separator"></div>
    <p class="bold">Pelanggan:</p>
    <p>{{ $order->customer_name }}</p>
    <p>{{ $order->vehicle_type }} - {{ $order->plate_number }}</p>
    @if($order->complaint)
    <p style="font-size:10px;color:#555">Keluhan: {{ $order->complaint }}</p>
    @endif
    <div class="separator"></div>
    <p class="bold">Rincian Barang:</p>
    @foreach($order->items as $item)
    <div class="row">
        <span>{{ $item->item_name }}</span>
    </div>
    <div class="row">
        <span>  {{ $item->quantity }} x Rp {{ number_format($item->price_at_time, 0, ',', '.') }}</span>
        <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
    </div>
    @endforeach
    <div class="separator"></div>
    <div class="row"><span>Total Barang:</span><span>Rp {{ number_format($order->total_items_cost, 0, ',', '.') }}</span></div>
    <div class="row"><span>Biaya Jasa:</span><span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span></div>
    <div class="separator"></div>
    <div class="row total-row"><span>TOTAL BAYAR:</span><span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span></div>
    <div class="separator"></div>
    <div class="center" style="margin-top:8px;">
        <p>Terima kasih atas kepercayaan Anda!</p>
        <p style="font-size:10px;margin-top:4px;">Garansi servis 3 hari</p>
    </div>

    <div class="no-print" style="margin-top:20px;text-align:center;">
        <button onclick="window.print()" style="padding:8px 20px;background:#f97316;color:white;border:none;border-radius:6px;cursor:pointer;font-size:14px;">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()" style="padding:8px 20px;background:#71717a;color:white;border:none;border-radius:6px;cursor:pointer;font-size:14px;margin-left:8px;">
            Tutup
        </button>
    </div>
</body>
</html>