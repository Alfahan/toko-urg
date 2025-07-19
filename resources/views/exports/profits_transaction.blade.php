<div class="title" style="padding-bottom: 13px">
    <div style="text-align: center;text-transform: uppercase;font-size: 15px">
        Toko URG
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
            <th scope="col">Customer</th>
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($profits as $profit)
        <tr>
            <td>{{ $profit->created_at }}</td>
            <td>{{ $profit->transaction->invoice }}</td>
            <td>{{
                $profit->transaction->customer && !empty($profit->transaction->customer->name)
                ? $profit->transaction->customer->name
                : 'Umum'
            }}</td>
            <td class="text-end">{{ formatPrice($profit->total) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL</td>
            <td class="text-end fw-bold" style="background-color: #e6e6e7;">{{ formatPrice($total) }}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-end fw-bold" style="background-color: #e6e6e7;">DISCOUNT</td>
            <td class="text-end fw-bold" style="background-color: #e6e6e7;">{{ formatPrice($discount) }}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL FIX</td>
            <td class="text-end fw-bold" style="background-color: #e6e6e7;">{{ formatPrice($total - $discount) }}</td>
        </tr>
    </tbody>
</table>

