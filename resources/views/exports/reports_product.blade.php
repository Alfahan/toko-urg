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
<table style="width: 100%">
    <thead>
        <tr style="background-color: #e6e6e7;">
            <th scope="col">Barcode</th>
            <th scope="col">Title</th>
            <th scope="col">Buy Price</th>
            <th scope="col">Sell Price</th>
            <th scope="col">Stock</th>
            <th scope="col">UoM</th>
            <th scope="col">Sub Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSum = 0;
        @endphp
        @foreach($products as $product)
        <tr>
            <td>{{ $product->barcode }}</td>
            <td>{{ $product->title }}</td>
            <td class="text-end">{{ formatPrice($product->buy_price) }}</td>
            <td class="text-end">{{ formatPrice($product->sell_price) }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->unit_of_measurement->name }}</td>
            <td class="text-end">{{ formatPrice($product->buy_price * $product->stock) }}</td>
        </tr>

        @php
            $subtotal = $product->buy_price * $product->stock;

            $totalSum += $subtotal;
        @endphp
        @endforeach
        <tr>
            <td colspan="6">ASSET</td>
            <td class="text-end font-weight-bold">{{ formatPrice($totalSum) }}</td>
        </tr>
    </tbody>
</table>
