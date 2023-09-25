<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produk;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Events\UserNotification;
use Illuminate\Support\HtmlString;


class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Blok Umum
    public function getResep(Request $request)
    {
        if ($request->ajax()) {
            $data = Resep::with('pasien', 'obat')
                ->where('isProceed', 0)
                ->get();
            return response()->json(['data' => $data]);
        } else {
            abort(400);
        }
    }

    public function apotekerProsesResep(Request $request, $kode)
    {
        Resep::where('kode', $kode)->update([
            'isProceedByApoteker' => 1,
        ]);
        event(new UserNotification('Apoteker telah memproses data Resep '.$kode, auth()->user(), '/dokter/resep/create/getAllResep', '0', 'Proses Sekarang'));

        return back()->with('success', 'Data Proses Berhasil di Proses');
    }

    public function notProcessedResep(Request $request)
    {
        if ($request->ajax()) {
            $dataExtracted = [];
            $data = Resep::with('pasien', 'dokter')
                ->where('isProceed', '1')
                ->where('apoteker_id', null)
                ->get();

            foreach ($data as $item) {
                    $dataExtracted[] = [
                        'kode' => $item->kode,
                        'obat_id' => count(json_decode($item->obat_id)),
                        'pasien_id' => $item->pasien->nama,
                    ];
                }

            return response()->json(['data' => $dataExtracted], 200);
        } else {
            return response('Server disconnected', 400);
        }
    }

    public function apotekerIndex()
    {
        // dd($dataExtracted);
        return view('apoteker.kasir.list', [
            'title' => 'Kasir',
            'produks' => Produk::all(),
            // 'datas' => $dataExtracted,
        ]);
    }

    public function apotekerIndexFilter(Request $request, $satuan)
    {
        if ($request->ajax()) {
            if ($satuan === 'Semua') {
                $data = Produk::get();
                return response()->json(['data' => $data]);
            } else {
                $data = Produk::where('satuan', '=', $satuan)->get();
                return response()->json(['data' => $data]);
            }
            $data = Produk::all();
            return response()->json(['data' => $data]);
        }
    }

    //  Blok Apoteker
    public function apotekerListResep(){
            $dataExtracted = [];
            $data = Resep::where('isProceed', '1')
                ->orderBy('created_at', 'desc')
                ->get();
            foreach ($data as $item) {
                $dataUser = User::all();
                $dataObat = Produk::whereIn('id', json_decode($item->obat_id));
                $bornAt = Carbon::parse(
                    $dataUser
                        ->where('id', $item->pasien_id)
                        ->pluck('tanggal_lahir')
                        ->first(),
                )->format('Y');
                $now = Carbon::now()->format('Y');
                $age = $now - $bornAt;
                $dataExtracted[] = [
                    'kodeTransaksi' => $item->kode,
                    'obat' => $dataObat->pluck('namaProduk')->toArray(),
                    'pasien' => $dataUser
                        ->where('id', $item->pasien_id)
                        ->pluck('nama')
                        ->first(),
                    'gejala' => $item->gejala,
                    'jumlah' => json_decode($item->jumlah),
                    'dokter' => $dataUser
                        ->where('id', $item->dokter_id)
                        ->pluck('nama', 'kategoriDokter')
                        ->toArray(),
                    'catatan' => json_decode($item->catatan),
                    'isProceed' => $item->isProceed,
                    'isProceedByApoteker' => $item->isProceedByApoteker,
                    'satuan' => json_decode($item->satuan),
                    'created_at' => $item->created_at,
                    'umur' => $age,
                ];
            }

            return view('apoteker.resep.list', [
                'title' => 'Daftar Resep Masuk',
                'data' => $dataExtracted,
            ]);
    }

    public function dokterCreate(Request $request)
    {
        $data = Resep::with('pasien', 'obat')
            ->where('isProceed', 0)
            ->get();
        return view('dokter.resep.create', [
            'title' => 'Buat Resep',
            'pasien' => $data,
            'obat' => Produk::all(),
            'satuan' => Produk::groupBy('satuan')->get(),
        ]);
    }

    // Blok Dokter
    public function dokterStore(Request $request)
    {
        if ($request->ajax()) {
            $check = Resep::where('kode', $request->kode)
                ->pluck('kode')
                ->first();
            if (isset($check)) {
                $data = [
                    'kode' => $request->kode,
                    'obat_id' => $request->obatId,
                    'jumlah' => $request->jumlah,
                    'satuan' => $request->satuan,
                    'catatan' => $request->catatan,
                    'isProceed' => 1,
                    'dokter_id' => auth()->user()->id,
                ];

                $updatedData = Resep::where('kode', $request->kode)->update($data);
                if ($updatedData) {
                    return response()->json(['message' => 'Data resep ' . $request->kode . ' berhasil disimpan'], 200);
                    event(new UserNotification('Dokter telah membuat resep baru '.$data['kode'], auth()->user(), '/apoteker/resep/antrian', '1', 'Buat Sekarang'));
                } else {
                    return response('Gagal Menyimpan Resep', 400);
                }
            } else {
                return response('Data tidak ditemukan', 404);
            }
        } else {
            return response('Server disconnected', 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function kasirNonResep()
    {
        return view('kasir.transaksi.nonresep', [
            'title' => 'Tanpa Resep',
        ]);
    }

    public function kasirWithResep()
    {
        return view('kasir.transaksi.transaksi-resep', [
            'title' => 'Dengan Resep',
            'pasiens' => User::where('level', '3')->get(),
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */

    public function kasirCreateResep(Request $request)
    {
        if ($request->ajax()) {
            $pasienId = User::where('kode', $request->kodePasien)
                ->pluck('id')
                ->first();
            $data = [
                'kode' => $request->kode,
                'pasien_id' => $pasienId,
                'gejala' => $request->gejala,
            ];
            event(new UserNotification('Kasir telah membuat permintaan Resep baru '.$data['kode'], auth()->user(), '/dokter/resep/create', '0', 'Buat Sekarang'));
            notification('Telah membuat resep dengan kode '.$data['kode'].'. Proses sekarang');
            Resep::create($data);
            return response()->json(['message' => 'Permintaan resep dan produk berhasil dikirimkan']);
        } else {
            abort(400);
        }
    }

    public function apotekerGetResepByKode(Request $request, $kode)
    {
        if ($request->ajax()) {
            $data = Resep::where('kode', $kode)->get();
            if (isset($data)) {
                $dataExtracted = [];
                foreach ($data as $item) {
                    $collectedProduk = Produk::whereIn('id', json_decode($item->obat_id))->get();
                    $dataExtracted[] = [
                        'kode' => $item->kode,
                        'price' => Produk::whereIn('id', json_decode($item['obat_id']))->pluck('harga'),
                        'stok' => Produk::whereIn('id', json_decode($item['obat_id']))->pluck('stok'),
                        'kodeProduk' => $collectedProduk->pluck('kode')->toArray(),
                        'namaProduk' => $collectedProduk->pluck('namaProduk')->toArray(),
                        'satuan' => json_decode($item->satuan),
                        'stok' => $collectedProduk->pluck('stok')->toArray(),
                        'harga' => $collectedProduk->pluck('harga')->toArray(),
                        'image' => $collectedProduk->pluck('image')->toArray(),
                        'namaDokter' => $item->dokter->nama,
                        'namaPasien' => $item->pasien->nama,
                        'created_at' => $item->created_at->format('d/m/y h:i'),
                    ];
                }
                return response()->json(['data' => $dataExtracted], 200);
            } else {
                return response('Data tidak ditemukan', 404);
            }
        } else {
            abort(400);
        }
    }


    public function edit(Resep $resep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResepRequest $request, Resep $resep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resep $resep)
    {
        //
    }
}
