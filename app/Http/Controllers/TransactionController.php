<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
     public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'service']) 
                           ->orderBy('tanggal_masuk', 'desc'); 

        if ($request->tanggal_mulai) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
        }
        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_sampai);
        }

        $transaksi = $query->paginate(25); 
        return view('transactions.index', compact('transaksi'));
    }

    public function Pdf(Request $request)
    {
        $query = Transaction::with(['customer', 'service'])
                           ->orderBy('tanggal_masuk', 'desc'); 

        if ($request->tanggal_mulai) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
        }
        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_sampai);
        }

        $transactions = $query->get(); 
        
        $pdf = Pdf::loadView('transactions.pdf', compact('transactions')); 
        return $pdf->stream('laporan-transaksi-' . date('Y-m-d') . '.pdf');
    }

    public function struk(Transaction $transaction)
    {
        $transaction->load(['customer', 'service']);
        return view('transactions.struk', compact('transaction'));
    }

    public function create()
    {
        $customers = Customer::all();
        $services = Service::all();
        return view('transactions.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'berat' => 'required|numeric|min:0.1',
            'tanggal_masuk' => 'required|date',
            'tanggal_ambil' => 'nullable|date|after_or_equal:tanggal_masuk',
        ]);

        $service = Service::find($request->service_id);
        $sub_total = $request->berat * $service->harga_per_kg;
        $diskon = $request->customer->diskon ?? 0;
        $total = $sub_total - ($sub_total * $diskon / 100);

        Transaction::create([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'berat' => $request->berat,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_ambil' => $request->tanggal_ambil,
            'sub_total' => $sub_total,
            'diskon' => $diskon,
            'total' => $total,
            'status' => $request->status ?? 'baru',
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'status_bayar' => $request->status_bayar ?? 'belum',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['customer', 'service']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $customers = Customer::all();
        $services = Service::all();
        return view('transactions.edit', compact('transaction', 'customers', 'services'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'berat' => 'required|numeric|min:0.1',
            'tanggal_masuk' => 'required|date',
            'tanggal_ambil' => 'nullable|date|after_or_equal:tanggal_masuk',
        ]);

        $service = Service::find($request->service_id);
        $sub_total = $request->berat * $service->harga_per_kg;
        $diskon = $request->customer->diskon ?? 0;
        $total = $sub_total - ($sub_total * $diskon / 100);

        $transaction->update([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'berat' => $request->berat,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_ambil' => $request->tanggal_ambil,
            'sub_total' => $sub_total,
            'diskon' => $diskon,
            'total' => $total,
            'status' => $request->status ?? 'proses',
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'status_bayar' => $request->status_bayar ?? 'belum',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diupdate!');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}