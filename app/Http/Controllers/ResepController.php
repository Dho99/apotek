<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resep;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
// use App\Events\UserNotification;
use Illuminate\Support\Facades\Validator;

class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Blok Umum
    public function getResepByKode(Request $request, $kode)
    {
        if ($request->ajax()) {
            $cekdata = Resep::where('kode', $kode)
                ->with('pasien', 'dokter', 'apoteker')
                ->first();
            if (isset($cekdata)) {
                return response()->json(['data' => $cekdata], 200);
            } else {
                return response('Data tidak ditemukan', 404);
            }
        } else {
            return response('Server disconnected', 400);
        }
    }

    public function getResep(Request $request)
    {
        if ($request->ajax()) {
            $data = Resep::with('pasien', 'obat')
                ->where([
                    'isProceed' => '0',
                ])
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
        // event(new UserNotification('Apoteker telah memproses data Resep '.$kode, auth()->user(), '/dokter/resep/create/getAllResep', '0', 'Proses Sekarang'));

        return back()->with('success', 'Data Proses Berhasil di Proses');
    }

    public function notProcessedResep(Request $request)
    {
        if ($request->ajax()) {
            $dataExtracted = [];
            $data = Resep::with('pasien', 'dokter')
                ->where([
                    'isProceed' => '1',
                    'isProceedByApoteker' => '1',
                    'isSuccess' => '0',
                ])
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
        return view('apoteker.kasir.list', [
            'title' => 'Kasir',
            'produks' => Produk::all(),
            'pasiens' => User::where('level', '3')->get(),
            'satuans' => Produk::groupBy('satuan')->get(),
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

    public function apotekerListResep()
    {
        $data = Resep::where([
            'isProceed' => '1',
        ])
            ->orderBy('created_at', 'desc')
            ->with('dokter', 'pasien')
            ->paginate(10);

        return view('apoteker.resep.list', [
            'title' => 'Daftar Resep Masuk',
            'data' => $data,
        ]);
    }

    public function dokterListResep()
    {
        $data = Resep::orderBy('created_at', 'desc')
            ->with('pasien', 'dokter', 'apoteker')
            ->paginate(10);

        return view('dokter.resep.list', [
            'title' => 'Rekap Resep',
            'data' => $data,
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

    public function dokterStore(Request $request)
    {
        if ($request->ajax()) {
            if (count(array_unique($request->obatId)) < count($request->obatId)) {
                return response('Gagal menyimpan resep karena duplikasi data', 500);
            } else {
                $check = Resep::where('kode', $request->kode)->first();
                $item = Produk::whereIn('id', $request->obatId)
                    ->pluck('satuan')
                    ->first();
                $satuanObat[] = $item;
                if (isset($check)) {
                    $data = [
                        'kode' => $request->kode,
                        'obat_id' => $request->obatId,
                        'jumlah' => $request->jumlah,
                        'satuan' => json_encode($satuanObat),
                        'catatan' => $request->catatan,
                        'isProceed' => 1,
                        'dokter_id' => auth()->user()->id,
                        'alasanPenolakan' => '',
                        'catatanDokter' => $request->catatanDokter,
                    ];

                    $updatedData = Resep::where('kode', $request->kode)->update($data);
                    if ($updatedData) {
                        // event(new UserNotification('Dokter telah membuat resep baru '.$data['kode'], auth()->user(), '/apoteker/resep/antrian', '1', 'Buat Sekarang'));
                        return response()->json(['message' => 'Data resep ' . $request->kode . ' berhasil disimpan'], 200);
                    } else {
                        return response('Gagal Menyimpan Resep', 400);
                    }
                } else {
                    return response('Data tidak ditemukan', 404);
                }
            }
        } else {
            return response('Server disconnected', 400);
        }
    }

    public function kasirNonResep()
    {
        return view('kasir.transaksi.nonresep', [
            'title' => 'Tanpa Resep',
        ]);
    }

    public function kasirWithResep()
    {
        return view('kasir.transaksi.transaksi-resep', [
            'title' => 'Buat Resep',
            'pasiens' => User::where('level', '3')->get(),
        ]);
    }

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
            // event(new UserNotification('Terdapat permintaan Resep baru '.$data['kode'], auth()->user(), '/dokter/resep/create', '0', 'Buat Sekarang'));
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
                        'kodeProduk' => $collectedProduk->pluck('kode'),
                        'namaProduk' => $collectedProduk->pluck('namaProduk'),
                        'satuan' => json_decode($item->satuan),
                        'stok' => $collectedProduk->pluck('stok'),
                        'harga' => $collectedProduk->pluck('harga'),
                        'image' => $collectedProduk->pluck('image'),
                        'namaDokter' => $item->dokter->nama,
                        'namaPasien' => $item->pasien->nama,
                        'created_at' => $item->created_at->format('d/m/y h:i'),
                        'catatanDokter' => $item->catatanDokter,
                        'catatan' => json_decode($item->catatan),
                        'namaApoteker' => $item->apoteker->nama,
                        'jumlah' => json_decode($item->jumlah)
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
    public function rejectResep(Request $request)
    {
        $cekResep = Resep::where('kode', $request->kodeResep)->first();
        $level = User::where('id', auth()->user()->id)
            ->pluck('level')
            ->first();
        if (isset($cekResep)) {
            if ($level == '0') {
                return response()->json(['data' => $request->all()], 200);
                $cekResep->update([
                    'isProceed' => 0,
                    'dokter_id' => auth()->user()->level,
                    'alasanPenolakan' => $request->alasan,
                ]);
            } elseif ($level == '1') {
                $cekResep->update([
                    'isProceed' => 0,
                    'isProceedByApoteker' => 0,
                    'apoteker_id' => auth()->user()->id,
                    'isSuccess' => 0,
                    'alasanPenolakan' => $request->alasan,
                ]);
            } else {
                return response(500);
            }
            return back();
            // return response()->json(['message' => 'Resep berhasil ditolak karena '.$request->alasan]);
        } else {
            return response('Data tidak ditemukan', 404);
        }
    }

    public function confirmResep(Request $request)
    {
        $cekResep = Resep::where('kode', $request->kodeResep)->first();
        if (isset($cekResep)) {
            $cekResep->update([
                'isProceedByApoteker' => '1',
                'apoteker_id' => auth()->user()->id,
            ]);
            return redirect()->intended('/apoteker/resep/antrian');
        } else {
            return response('Data tidak ditemukan', 404);
        }
        return response(500);
        // return response()->json(['message' => 'Resep berhasil ditolak karena '.$request->alasan]);
    }
}
