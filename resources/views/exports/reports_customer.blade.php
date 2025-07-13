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
            <th scope="col">Name</th>
            <th scope="col">No Telp</th>
            <th scope="col">Address</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->no_telp }}</td>
            <td>{{ $customer->address }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
