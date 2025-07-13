<html moznomarginboxes mozdisallowselectionprint>

<head>
    <title>
        Purchase Order
    </title>
    <link href='https://fonts.googleapis.com/css?family=Alexandria' rel='stylesheet'>
    <style type="text/css">
        html {
            font-family: 'Alexandria';
            font-size: 14px;
            font-weight: bold;
        }

        .content {
            width: 58mm;
            font-size: 14px;
            padding: 10px;
        }

        .content .title {
            text-align: center;
        }

        .content .head-desc {
            margin-top: 10px;
            display: table;
            width: 100%;
        }

        .content .head-desc>div {
            display: table-cell;
        }

        .content .head-desc .user {
            text-align: right;
        }

        .content .nota {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .content .separate {
            margin-top: 10px;
            margin-bottom: 15px;
            border-top: 1px dashed #000;
        }

        .content .transaction-table {
            width: 95%;
            font-size: 12px;
        }

        .content .transaction-table .name {
            /*//width: 185px;*/
        }

        .content .transaction-table .qty {
            /*//text-align: center;*/
            /*width: 65px;*/
        }

        .content .transaction-table .sell-price {
            /*//text-align: right;*/
            width: 65px;
            text-align: right;
        }

        .content .transaction-table .final-price {
            text-align: right;
        }

        .content .transaction-table tr td {
            vertical-align: top;
        }

        .content .transaction-table .price-tr td {
            padding-top: 7px;
            padding-bottom: 7px;
        }

        .content .transaction-table .discount-tr td {
            padding-top: 7px;
            padding-bottom: 7px;
        }

        .content .transaction-table .separate-line {
            height: 1px;
            border-top: 1px dashed #000;
        }

        .content .thanks {
            margin-top: 25px;
            text-align: center;
        }

        .content .azost {
            margin-top: 5px;
            text-align: center;
            font-size: 10px;
        }

        @media print {
            @page {
                width: 58mm;
                margin: 0mm;
            }
        }
    </style>
    <script>
        window.print();
        window.onafterprint = function() {
            setTimeout(function() {
                window.close();
            }, 1000);
        }
    </script>
</head>

<body>
    <div class="content">
        <div class="title" style="padding-bottom: 13px">
            <div style="text-align: center;text-transform: uppercase;font-size: 15px">
                Toko Ramadhan
            </div>
            <div style="text-align: center">
                Alamat: Jalan Gono Tirtowidjoyo No 678 Dusun II Kp Kebon Kalapa RT 007/004 DS Kutapohaci, Ciampel
            </div>
            <div style="text-align: center">
                Telp: 085285589396
            </div>
        </div>

        <div class="separate-line" style="border-top: 1px dashed #000;height: 1px;margin-bottom: 5px"></div>
        <table class="transaction-table" cellspacing="0" cellpadding="0">
            <tr>
                <td>TANGGAL</td>
                <td>:</td>
                <td>{{ $transaction->created_at }}</td>
            </tr>
            <tr>
                <td>FAKTUR</td>
                <td>:</td>
                <td>{{ $transaction->invoice }}</td>
            </tr>
            <tr>
                <td>ADMIN</td>
                <td>:</td>
                <td>{{ $transaction->admin->name ?? '' }}</td>
            </tr>
            <tr>
                <td>SUPPLIER</td>
                <td>:</td>
                <td>{{ $transaction->supplier->name_company ?? 'Umum' }}</td>
            </tr>
        </table>

        <div class="transaction">
            <table class="transaction-table" cellspacing="0" cellpadding="0">
                <tr class="price-tr">
                    <td colspan="3">
                        <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                    </td>
                    <td colspan="3">
                        <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                    </td>
                    <td colspan="3">
                        <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                    </td>
                </tr>
                @foreach ($transaction->purchase_transaction_details()->get() as $item)
                <tr>
                    <!-- Baris 1: Nama produk -->
                    <td class='name' style='text-align: left' colspan="12">
                        {{ Str::upper($item->product->title) }}
                    </td>
                </tr>
                <tr>
                    <!-- Baris 2: Harga per item, Qty, dan Sub Total -->
                    <td style='text-align: left' colspan="4">
                        {{ formatPriceWithoutRp($item->price_per_qty) }}
                    </td>
                    <td style='text-align: center' colspan="4">
                        x{{ $item->qty }}
                    </td>
                    <td class='final-price' style='text-align: right' colspan="4">
                        {{ formatPriceWithoutRp($item->price) }}
                    </td>
                </tr>
                @endforeach
                <tr class="price-tr">
                    <td colspan="3">
                        <div class="separate-line"></div>
                    </td>
                    <td colspan="3">
                        <div class="separate-line"></div>
                    </td>
                    <td colspan="3">
                        <div class="separate-line"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="final-price">
                        TOTAL
                    </td>
                    <td colspan="3" class="final-price">
                        :
                    </td>
                    <td class="final-price">
                        {{ formatPriceWithoutRp($transaction->grand_total) }}
                    </td>
                </tr>

                <tr class="discount-tr">
                    <td colspan="3">
                        <div class="separate-line"></div>
                    </td>
                    <td colspan="3">
                        <div class="separate-line"></div>
                    </td>
                    <td colspan="3">
                        <div class="separate-line"></div>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" class="final-price">
                        TUNAI
                    </td>
                    <td colspan="3" class="final-price">
                        :
                    </td>
                    <td class="final-price">
                        {{ formatPriceWithoutRp($transaction->cash) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="final-price">
                        KEMBALI
                    </td>
                    <td colspan="3" class="final-price">
                        :
                    </td>
                    <td class="final-price">
                        {{ formatPriceWithoutRp($transaction->change) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
