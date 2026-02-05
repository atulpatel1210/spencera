<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order - {{ $order->po }}</title>
    <style>
        /* BASE STYLES */
        body { 
            font-family: 'Helvetica Neue', Arial, sans-serif; 
            font-size: 10pt; 
            color: #333; 
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; 
        }
        .po-container { 
            width: 95%; 
            max-width: 900px; /* Increased width to prevent cutting */
            margin: 20px auto; 
            padding: 20px; 
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }

        /* --- CRITICAL FIXES FOR PRINTING (Colors and Page Breaks) --- */
        /* Force background colors and images to print */
        * { 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }

        /* Prevent table rows from splitting across pages */
        .description-table tbody tr {
            page-break-inside: avoid;
        }

        /* HEADER STYLES */
        .po-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end; /* Align title and logo to the bottom */
            border-bottom: 5px solid #000; /* Thicker, defined bottom line */
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        .logo-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-img {
            padding: 8px 12px;
            border-radius: 4px;
        }
        .po-title {
            text-align: right;
            font-size: 28pt; /* Larger title */
            font-weight: 900;
            color: #000;
            margin: 0;
        }

        /* INFO BOXES STYLES */
        .manufacturer-info, .exporter-info {
            padding: 12px;
            border: 1px solid #ccc;
            border-left: 5px solid #ef7c1b; /* Accent color bar on the left */
            border-radius: 4px;
            margin-bottom: 15px;
            line-height: 2; /* Better line spacing */
            min-height: 90px;
            font-size: 10pt;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            gap: 25px;
            margin-bottom: 25px;
        }
        .po-meta-box {
            background-color: #f7f7f7; /* Lighter background for PO details */
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .po-meta-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .po-meta-item strong {
            color: #000;
        }
        .po-number {
            font-weight: bold;
        }

        /* DATA TABLE STYLES */
        .description-table {
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px;
        }
        .description-table th, .description-table td {
            border: 1px solid #a19c9c; /* Darker border for table clarity */
            padding: 7px 10px; 
            text-align: center;
            vertical-align: middle;
            font-size: 9pt;
        }
        .description-table thead th { 
            background-color: #e0e0e0; 
            color: #000; 
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .description-table tbody tr:nth-child(even) {
            background-color: #f9f9f9; 
        }

        /* PALLET DETAIL GROUPING */
        .pallet-detail-header th {
            background-color: #5555558f !important;
        }
        
        /* TOTALS AND FOOTER STYLES */
        .total-row td {
            font-size: 11pt;
            background-color: #e0e0e0 !important;
            font-weight: bold;
            border-top: 2px solid #a19c9c !important;
        }
        .footer-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 15px;
        }
        .signatures {
            text-align: right;
            line-height: 1.5;
            padding-right: 10px;
        }
        .remarks {
            border: 1px solid #ddd;
            padding: 12px;
            min-height: 50px;
            margin-top: 5px;
            margin-bottom: 20px;
            background-color: #fcfcfc;
            line-height: 1.5;
        }
        .strong-label {
            font-weight: bold;
            color: #222;
            display: block;
            margin-bottom: 5px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 2px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Print Styles: Minimize margins for maximum content space */
        @media print {
            body { 
                margin: 0; /* Remove body margin */
            }
            .po-container { 
                box-shadow: none; 
                margin: 0;
                padding: 10mm; /* Use a small physical margin */
            }
            /* Force background printing for images within the logo box */
            .logo-img img {
                /* background-color: #000 !important; */
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
        }
    </style>
</head>
<body>

<div class="po-container">
    
    <div class="po-header">
        <div class="logo-box">
            <div class="logo-img">
                <img src="{{ asset('images/logo_black.png') }}" alt="Logo" height="40">
            </div>
        </div>
        <h1 class="po-title">PRODUCTION ORDER</h1>
    </div>

    <div class="header-section">
        
        <div style="width: 55%;">
            <div class="manufacturer-info">
                <span class="strong-label">MANUFACTURER:</span>
                @if($companyDetail)
                    <strong>{{ $companyDetail->name ?? 'N/A' }}</strong><br>
                    {{ $companyDetail->address_line1 ?? '' }} {{ $companyDetail->address_line2 ?? '' }}<br>
                    {{ $companyDetail->city ?? '' }}, {{ $companyDetail->state ?? '' }}, {{ $companyDetail->zip ?? '' }}
                @else
                    (Company details not set)
                @endif
            </div>
            <?php
            if(!empty($order->party) && strtolower($order->party->party_type) != 'export') {
            ?>
            <div class="manufacturer-info">
                <span class="strong-label">EXPORTER:</span>
                <strong>{{ $order->party?->party_name ?? 'N/A' }}</strong><br>
                {{ $order->party?->address ?? 'N/A' }}<br>
                TEL: {{ $order->party?->phone ?? 'N/A' }}
            </div>
            <?php
            } ?>
        </div>
        
        <div style="width: 40%;">
            <div class="po-meta-box">
                <div class="po-meta-item">
                    <span>PO NO:</span>
                    <span class="po-number">{{ $order->po }}</span>
                </div>
                <div class="po-meta-item">
                    <span>DATE:</span>
                    <strong>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</strong>
                </div>
                <div class="po-meta-item">
                    <span>BRAND NAME:</span>
                    <strong>{{ $order->brand_name }}</strong>
                </div>
                <hr style="border-top: 1px solid #ccc; margin: 10px 0;">
                <div style="text-align: center; margin-bottom: 5px;">
                    <span class="strong-label" style="text-align: center; border: none;">BOX IMAGE</span>
                </div>
                <div class="po-meta-item" style="justify-content: center;">
                    <img src="{{ asset('storage/box_images/'.$order->box_image) }}"  alt="Box Image" height="200" width="335" style="background-color: #000; padding: 5px; border-radius: 2px;">
                </div>
            </div>
        </div>
    </div>
    
    <h3 style="margin: 5px 0 10px 0; border-left: 5px solid #ef7c1b; padding-left: 10px; color: #222;">DESCRIPTION OF GOODS</h3>
    
    <table class="description-table">
        <thead>
            <tr>
                <th rowspan="2" width="8%">SIZE</th>
                <th rowspan="2" width="12%">DESIGN</th>
                <th rowspan="2" width="7%">FINISH</th>
                <th rowspan="2" width="12%">DESIGN PHOTO</th>
                <th rowspan="2" width="10%">REMARK</th>
                <th colspan="3" class="pallet-detail-header" width="30%">PALLET DETAIL</th>
                <th rowspan="2" width="10%">TOTAL BOX</th>
            </tr>
            <tr class="pallet-detail-header">
                <th width="10%">BOX/PALLET</th>
                <th width="8%">TOTAL PALLET</th>
                <th width="12%">QNTY (BOX)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalBox = 0;
                $allRemarks = [];
            @endphp
            
            @foreach ($order->orderItems as $item)
                @php
                    $design = $item->designDetail;
                    $size = $item->sizeDetail;
                    $finish = $item->finishDetail;

                    $pallets = json_decode($item->pallets, true) ?? [];
                    $palletCount = count($pallets);
                    $firstPallet = $pallets[0] ?? null;
                    $itemTotalBoxes = $item->order_qty;
                    
                    $grandTotalBox += $itemTotalBoxes;
                    
                    if (!empty($item->remark)) {
                        $allRemarks[] = $item->remark;
                    }
                @endphp
                
                <tr>
                    {{-- SIZE --}}
                    <td rowspan="{{ $palletCount > 1 ? $palletCount : 1 }}">{{ $size?->size_name ?? 'N/A' }}</td>
                    {{-- DESIGN --}}
                    <td rowspan="{{ $palletCount > 1 ? $palletCount : 1 }}">{{ $design?->name ?? 'N/A' }}</td>
                    {{-- FINISH --}}
                    <td rowspan="{{ $palletCount > 1 ? $palletCount : 1 }}">{{ $finish?->finish_name ?? 'N/A' }}</td>
                    
                    {{-- DESIGN PHOTO (Null Check) --}}
                    <td rowspan="{{ $palletCount > 1 ? $palletCount : 1 }}" class="text-center">
                        @if ($design && $design->image)
                            <img src="{{ asset('storage/designs/'.$design->image) }}" width="100" height="50">
                        @else
                            N/A
                        @endif
                    </td>

                    {{-- REMARK --}}
                    <td rowspan="{{ $palletCount > 1 ? $palletCount : 1 }}" class="text-center small">
                        {{ $item->remark ?? '-' }}
                    </td>
                    
                    {{-- PALLET DETAILS (First Row) --}}
                    @if ($firstPallet)
                        <td class="text-center">{{ $firstPallet['box_pallet'] }}</td>
                        <td class="text-center">{{ $firstPallet['total_pallet'] }}</td>
                        <td class="text-right">{{ $firstPallet['box_pallet'] * $firstPallet['total_pallet'] }}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                    
                    {{-- TOTAL BOX --}}
                    <td rowspan="{{ $palletCount > 1 ? $palletCount : 1 }}" class="text-right total-row-item" style="font-weight: bold;">
                        {{ number_format($itemTotalBoxes) }}
                    </td>
                </tr>
                
                {{-- PALLET DETAILS (Additional Rows) --}}
                @if ($palletCount > 1)
                    @foreach (array_slice($pallets, 1) as $pallet)
                        <tr>
                            <td class="text-center">{{ $pallet['box_pallet'] }}</td>
                            <td class="text-center">{{ $pallet['total_pallet'] }}</td>
                            <td class="text-right">{{ $pallet['box_pallet'] * $pallet['total_pallet'] }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
            
            <tr class="total-row">
                <td colspan="8" class="text-right">GRAND TOTAL</td>
                <td class="text-right">
                    {{ number_format($grandTotalBox) }}
                </td>
            </tr>
        </tbody>
    </table>
    
    <span class="strong-label">REMARKS:</span>
    <div class="remarks">
        @if (count($allRemarks) > 0)
            {{ implode('; ', array_unique($allRemarks)) }}
        @else
            <em style="color: #888;">No specific remarks for this order.</em>
        @endif
    </div>


    <div class="footer-section">
        <div style="width: 50%; line-height: 1.8;">
        </div>
        <div class="signatures">
            PREPARED BY<br><br><br>
            <strong style="border-top: 1px solid #000; padding-top: 5px; display: inline-block;">(AUTHORISED SIGNATORY)</strong>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 30px; color: #888; font-size: 8pt;">
        <small>This is a computer generated document and does not require a signature.</small>
    </div>

</div>

<script>
    window.onload = function() {
        // window.print();
    }
</script>

</body>
</html>