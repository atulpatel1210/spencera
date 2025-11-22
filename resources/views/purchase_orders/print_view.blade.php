<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order - {{ $order->po }}</title>
    <style>
        /* BASE STYLES */
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #000; }
        .po-container { width: 100%; max-width: 800px; margin: 0 auto; padding: 10px; }
        
        /* TABLE STYLES */
        .info-table, .description-table, .totals-table, .remark-table {
            width: 100%; border-collapse: collapse; margin-bottom: 10px;
        }
        .info-table th, .info-table td,
        .description-table th, .description-table td,
        .totals-table td, .remark-table td {
            border: 1px solid #000; padding: 4px; text-align: left;
            vertical-align: top;
        }
        
        /* NO BORDER STYLE */
        .no-border, .no-border td, .no-border th { border: none !important; padding: 2px 4px; }
        
        /* ALIGNMENT */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .description-table th { background-color: #f0f0f0; }
    </style>
</head>
<body>

<div class="po-container">
    
    <table class="header-table no-border">
        <tr>
            <td colspan="2" class="no-border">
                <img src="{{ asset('path/to/your/exporter_logo.png') }}" alt="Exporter Logo" height="40"><br>
                <strong>BHABHA EXPORTS</strong>
            </td>
            <td colspan="2" class="text-center no-border">
                <h3 style="margin: 0;">PURCHASE ORDER</h3>
            </td>
        </tr>
    </table>
    
    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>EXPORTERS:</strong><br>
                {{ $order->party?->party_name ?? 'N/A' }}<br>
                {{ $order->party?->address ?? 'N/A' }}<br>
                MORBI - INDIA, <br>
                TEL: {{ $order->party?->phone ?? 'N/A' }}
            </td>
            <td width="50%">
                <table class="no-border" style="width: 100%;">
                    <tr><td width="50%">PO NO:</td><td><strong>{{ $order->po }}</strong></td></tr>
                    <tr><td>DATE:</td><td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td></tr>
                    <tr><td>BRAND NAME:</td><td>{{ $order->brand_name }}</td></tr>
                </table>
            </td>
        </tr>
        <tr>
             <td>
                @if($companyDetail)
                    <strong>MANUFACTURER:</strong><br>
                    {{ $companyDetail->name ?? 'N/A' }} LLP:<br>
                    {{ $companyDetail->address_line1 ?? '' }} {{ $companyDetail->address_line2 ?? '' }}<br>
                    {{ $companyDetail->city ?? '' }}, {{ $companyDetail->state ?? '' }}, {{ $companyDetail->zip ?? '' }}
                @else
                    <strong>MANUFACTURER:</strong><br>
                    (Company details not set)
                @endif
            </td>
            <td>
                <table class="no-border" style="width: 100%;">
                    <tr><td width="50%">TERMS OF DELIVERY:</td><td>MUNDRA PORT</td></tr>
                    <tr><td>PAYMENT TERMS:</td><td>ADVANCE</td></tr>
                </table>
            </td>
        </tr>
    </table>
    
    <h4 class="text-center" style="margin: 5px 0;">DESCRIPTIONS OF GOODS</h4>
    
    <table class="description-table">
        <thead>
            <tr>
                <th rowspan="2" width="15%">SIZE</th>
                <th rowspan="2" width="15%">DESIGN</th>
                <th rowspan="2" width="10%">FINISH</th>
                <th rowspan="2" width="10%">DESIGN PHOTO</th>
                <th colspan="3" width="30%">PALLET DETAIL</th>
                <th rowspan="2" width="10%">TOTAL BOX</th>
            </tr>
            <tr>
                <th width="10%">BOX/PALLET</th>
                <th width="10%">TOTAL PALLET</th>
                <th width="10%">QNTY (BOX)</th>
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
                        @if ($design && $design->photo_path)
                            <img src="{{ asset('storage/design_photos/' . $design->photo_path) }}" alt="Design Photo" style="max-height: 50px; max-width: 50px;">
                        @else
                            N/A
                        @endif
                    </td>
                    
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
                    <td rowspan="{{ $palletCount > 1 ? $palletCount : 1 }}" class="text-right">
                        {{ number_format($itemTotalBoxes) }}
                    </td>
                </tr>
                
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
            
            <tr>
                <td colspan="7" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right">
                    <strong>{{ number_format($grandTotalBox) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
    
    <h5 style="margin: 5px 0;">REMARKS:</h5>
    <table class="remark-table">
        <tr>
            <td style="min-height: 50px;">
                @if (count($allRemarks) > 0)
                    {{ implode('; ', array_unique($allRemarks)) }}
                @else
                    N/A
                @endif
            </td>
        </tr>
    </table>


    <table class="totals-table">
        <tr>
            <td width="50%">
                COMPANY'S PAN: <strong>{{ $companyDetail->pan_number ?? 'N/A' }}</strong><br>
                COMPANY'S GST NO: <strong>{{ $companyDetail->gst_no ?? 'N/A' }}</strong>
            </td>
            <td width="50%">
                PREPARED BY<br><br><br>
                D. A. KAVAR
            </td>
        </tr>
    </table>
    
    <div style="text-align: center; margin-top: 20px;">
        <!-- <img src="{{ asset('path/to/your/footer_logo.png') }}" alt="NKT" height="40"><br> -->
        <small>This is a computer generated document and does not require a signature.</small>
    </div>

</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>