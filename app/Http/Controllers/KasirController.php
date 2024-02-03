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

class KasirController extends Controller
{
    public function index()
    {
        $produk = produk::all();
        $purchases = session('purchases', []);
        $totalHarga = $this->hitungTotalHarga($purchases);

        return view('index', compact('produk', 'purchases', 'totalHarga'));
    }

    public function tambahBarang(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $produk = produk::find($request->produk_id);

        // Cek stok barang
        if ($produk->stok >= $request->jumlah) {
            $purchases = session('purchases', []);
            $purchases[] = [
                'produk_id' => $produk->id,
                'nama_poduk' => $produk->nama,
                'jumlah' => $request->jumlah,
                'harga' => $produk->harga,
                'total_harga' => $produk->harga * $request->jumlah,
            ];

            // Kurangi stok barang
            $produk->stok -= $request->jumlah;
            $produk->save();

            session(['purchases' => $purchases]);

            $totalHarga = $this->hitungTotalHarga($purchases);

            return redirect('/kasir')->with('totalHarga', $totalHarga);
        } else {
            return redirect('/kasir')->with('error', 'Stok barang tidak mencukupi.');
        }
    }

    private function hitungTotalHarga($purchases)
    {
        return collect($purchases)->sum('total_harga');
    }
}
