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
            <th scope="col">Date</th>
            <th scope="col">Invoice</th>
            <th scope="col">Admin</th>
            <th scope="col">Name Item</th>
            <th scope="col">Price Item</th>
            <th scope="col">Qty</th>
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cost as $item)
        <tr>
            <td>{{ $item->created_at }}</td>
            <td>{{ $item->cost_transaction->invoice }}</td>
            <td>{{ $item->cost_transaction->admin->name ?? '' }}</td>
            <td>{{ $item->name_cost }}</td>
            <td>{{ formatPrice($item->price_per_qty) }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ formatPrice($item->price) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="6" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL</td>
            <td class="text-end fw-bold" style="background-color: #e6e6e7;">{{ formatPrice($total) }}</td>
        </tr>
    </tbody>
</table>
