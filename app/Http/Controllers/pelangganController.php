<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use App\Models\produk;
use App\Models\penjualan;
use App\Models\pelanggan;
use App\Models\detail_penjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;


class pelangganController extends Controller
{
    //

    public function tambah_pelanggan()
    {
        return view('/tambah_pelanggan');
    }

    function proses_tambah_pelanggan(Request $request)
    {
        $nama_pelanggan = $request->nama_pelanggan;
        $alamat = $request->alamat;
        $nomor_telepon = $request->nomor_telepon;
        $pelanggan_id = $request->pelanggan_id;

        DB::table('pelanggan')->insert([

            'nama_pelanggan' => $nama_pelanggan,
            'alamat' => $alamat,
            'nomor_telepon' => $nomor_telepon
        ]);

        return redirect('/pelanggan')->with('pelanggan_id', $pelanggan_id);
    }

    function pelanggan()
    {
        $pelanggan = DB::table('pelanggan')->get(); // Sesuaikan dengan model dan query yang sesuai
        return view('/pelanggan', ['pelanggan' => $pelanggan]);
    }

    function hapus_pelanggan($id)
    {
        echo $id;
        $deleted = DB::table('pelanggan')->where('pelanggan_id', $id)->delete();
        if ($deleted) {
            return redirect('/pelanggan');
        }
    }

    function tampil_update_pelanggan($id)
    {
        $pelanggan = DB::table('pelanggan')->where('pelanggan_id', $id)->first();
        return view('update_pelanggan', ['pelanggan' => $pelanggan]);
    }

    function proses_update_pelanggan(Request $request, $id)
    {
        $nama_pelanggan = $request->nama_pelanggan;
        $alamat = $request->alamat;
        $nomor_telepon = $request->nomor_telepon;

        DB::table('pelanggan')
            ->where('pelanggan_id', $id)
            ->update([
                'nama_pelanggan' => $nama_pelanggan,
                'alamat' => $alamat,
                'nomor_telepon' => $nomor_telepon
            ]);
        return redirect('/pelanggan');
    }
}