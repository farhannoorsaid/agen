<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            color: #333;
            line-height: 1.4;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 { 
            text-align: center; 
            margin-bottom: 5px; 
            font-size: 16px;
            color: #000;
        }
        
        .date { 
            text-align: center; 
            color: #666; 
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        .summary { 
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px; 
            page-break-inside: avoid;
        }
        
        .summary-item { 
            text-align: center; 
            padding: 12px;
            background: #f5f5f5; 
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .summary-label { 
            color: #666; 
            font-size: 10px;
            margin-bottom: 5px;
        }
        
        .summary-value { 
            font-weight: bold; 
            font-size: 12px; 
            color: #333;
            word-break: break-word;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
            page-break-inside: avoid;
        }
        
        th { 
            background-color: #27ae60; 
            color: white; 
            padding: 8px; 
            text-align: left; 
            font-weight: bold; 
            border: 1px solid #333; 
            font-size: 10px;
        }
        
        td { 
            padding: 6px 8px; 
            border: 1px solid #ddd; 
            font-size: 10px;
        }
        
        tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
        
        .total-row { 
            background-color: #d5f4e6; 
            font-weight: bold; 
        }
        
        .status-lunas { 
            color: #28a745; 
            font-weight: bold;
        }
        
        .status-belum { 
            color: #dc3545; 
            font-weight: bold;
        }
        
        .footer-summary {
            margin-top: 20px; 
            font-size: 10px; 
            color: #666;
            page-break-inside: avoid;
        }
        
        .footer-summary p {
            margin: 5px 0;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                padding: 0;
            }
            .summary {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 10px;
                page-break-inside: avoid;
            }
            table {
                page-break-inside: avoid;
            }
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>LAPORAN PENJUALAN</h1>
        <p class="date">Tanggal: {{ now()->format('d M Y H:i') }}</p>

        <div class="summary">
            <div class="summary-item">
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value">{{ $stockOuts->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Qty</div>
                <div class="summary-value">{{ $totalQty }} unit</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Belum Lunas</div>
                <div class="summary-value" style="color: #dc3545;">Rp {{ number_format($totalBelum, 0, ',', '.') }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Supplier</th>
                    <th>Qty</th>
                    <th>Harga/Unit</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockOuts as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>{{ $item->product_name_snapshot }}</td>
                        <td>{{ $item->supplier_name_snapshot }}</td>
                        <td style="text-align: center;">{{ $item->jumlah_terjual }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->price_snapshot / $item->jumlah_terjual, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td class="{{ $item->status_pembayaran === 'lunas' ? 'status-lunas' : 'status-belum' }}">
                            {{ $item->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #999;">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4">TOTAL</td>
                    <td style="text-align: center;">{{ $totalQty }}</td>
                    <td></td>
                    <td style="text-align: right;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="footer-summary">
            <p><strong>Lunas:</strong> Rp {{ number_format($totalLunas, 0, ',', '.') }}</p>
            <p><strong>Belum Lunas:</strong> Rp {{ number_format($totalBelum, 0, ',', '.') }}</p>
        </div>
    </div>
</body>
</html>
