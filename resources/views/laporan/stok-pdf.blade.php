<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang</title>
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
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
            page-break-inside: avoid;
        }
        
        th { 
            background-color: #4A90E2; 
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
            background-color: #e8e8e8; 
            font-weight: bold; 
        }
        
        .status-low { 
            color: #dc3545; 
            font-weight: bold; 
        }
        
        .status-normal { 
            color: #28a745; 
            font-weight: bold;
        }
        
        .summary {
            margin-top: 20px; 
            text-align: right; 
            font-size: 10px; 
            color: #666;
            page-break-inside: avoid;
        }
        
        .summary p {
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
        <h1>LAPORAN STOK BARANG</h1>
        <p class="date">Tanggal: {{ now()->format('d M Y H:i') }}</p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Min</th>
                    <th>Harga Jual</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td style="text-align: center;">{{ $item->stok }}</td>
                        <td style="text-align: center;">{{ $item->stok_minimum }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td class="{{ $item->stok <= $item->stok_minimum ? 'status-low' : 'status-normal' }}">
                            {{ $item->stok <= $item->stok_minimum ? 'Stok Rendah' : 'Normal' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary">
            <p>Total Barang: <strong>{{ $barang->count() }}</strong></p>
            <p>Total Stok: <strong>{{ $barang->sum('stok') }} unit</strong></p>
        </div>
    </div>
</body>
</html>
