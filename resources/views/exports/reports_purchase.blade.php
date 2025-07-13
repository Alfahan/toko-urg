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
            <th scope="col">Supplier</th>
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchases as $purchase)
        <tr>
            <td>{{ $purchase->created_at }}</td>
            <td>{{ $purchase->invoice }}</td>
            <td>{{ $purchase->admin->name ?? '' }}</td>
            <td>{{ $purchase->supplier->name_company ?? 'Umum' }}</td>
            <td class="text-end">{{ formatPrice($purchase->grand_total) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL</td>
            <td class="text-end fw-bold" style="background-color: #e6e6e7;">{{ formatPrice($total) }}</td>
        </tr>
    </tbody>
</table>
