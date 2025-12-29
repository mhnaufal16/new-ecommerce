@extends('layouts.admin')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-shopping-bag me-2 text-primary"></i>Detail Pesanan #{{ $order->order_number }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm border">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-primary"></i>Item Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center py-2">
                                            @if($item->product && $item->product->id)
                                                <div class="bg-light rounded-3 p-1 me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $item->product_name }}</div>
                                                @if($item->variant_name)
                                                    <small class="text-muted">{{ $item->variant_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->formatted_price }}</td>
                                    <td class="text-end pe-4 fw-bold text-primary">{{ $item->formatted_total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end py-2">Subtotal</td>
                                    <td class="text-end pe-4 py-2">{{ $order->formatted_subtotal }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end py-2 text-danger">Potongan Kupon</td>
                                    <td class="text-end pe-4 py-2 text-danger">-{{ $order->formatted_discount_amount }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end py-2">Biaya Pengiriman</td>
                                    <td class="text-end pe-4 py-2">{{ $order->formatted_shipping_amount }}</td>
                                </tr>
                                <tr class="bg-white border-top">
                                    <td colspan="3" class="text-end py-3 pt-4 border-0"><h5 class="fw-bold mb-0">Total Akhir</h5></td>
                                    <td class="text-end pe-4 py-3 pt-4 border-0"><h4 class="fw-bold mb-0 text-primary">{{ $order->formatted_grand_total }}</h4></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Addresses Info -->
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Informasi Pengiriman & Penagihan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 d-flex align-items-stretch">
                        <div class="col-md-6 d-flex flex-column">
                            <h6 class="fw-bold text-dark mb-3">
                                <span class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-2">
                                    <i class="fas fa-truck-loading"></i>
                                </span> Alamat Pengiriman
                            </h6>
                            <div class="p-3 rounded-4 border bg-light bg-opacity-50 flex-grow-1">
                                @if($order->shippingAddress)
                                    <p class="mb-1 fw-bold">{{ $order->shippingAddress->recipient_name }}</p>
                                    <p class="mb-2 small text-muted"><i class="fas fa-phone-alt me-2 fa-xs"></i>{{ $order->shippingAddress->phone }}</p>
                                    <p class="mb-0 small text-muted lh-base">
                                        {{ $order->shippingAddress->address }}<br>
                                        {{ $order->shippingAddress->subdistrict }}, {{ $order->shippingAddress->district }}<br>
                                        {{ $order->shippingAddress->city_name }}, {{ $order->shippingAddress->province_name }} {{ $order->shippingAddress->postal_code }}
                                    </p>
                                @else
                                    <div class="d-flex align-items-center h-100 justify-content-center py-4">
                                        <p class="text-muted small mb-0 italic"><i class="fas fa-info-circle me-2"></i>Data pengiriman tidak tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column">
                            <h6 class="fw-bold text-dark mb-3">
                                <span class="bg-success bg-opacity-10 text-success p-2 rounded-3 me-2">
                                    <i class="fas fa-receipt"></i>
                                </span> Alamat Penagihan
                            </h6>
                            <div class="p-3 rounded-4 border bg-light bg-opacity-50 flex-grow-1">
                                @if($order->billingAddress)
                                    <p class="mb-1 fw-bold">{{ $order->billingAddress->recipient_name }}</p>
                                    <p class="mb-0 small text-muted lh-base">
                                        {{ $order->billingAddress->address }}<br>
                                        {{ $order->billingAddress->city_name }}, {{ $order->billingAddress->province_name }}
                                    </p>
                                @else
                                    <div class="d-flex align-items-center h-100 justify-content-center py-4 text-center">
                                        <div>
                                            <i class="fas fa-copy text-muted mb-2 d-block opacity-50" style="font-size: 1.5rem;"></i>
                                            <p class="text-muted small mb-0">Sama dengan Alamat Pengiriman</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Management Sidebar -->
        <div class="col-lg-4">
            <!-- Order Status Card -->
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-cog me-2 text-primary"></i>Kelola Pesanan</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <label class="form-label fw-bold small">Status Pesanan</label>
                        <div class="input-group">
                            <select name="status" class="form-select rounded-start-3">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="on_hold" {{ $order->status == 'on_hold' ? 'selected' : '' }}>Ditahan</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refund</option>
                            </select>
                            <button type="submit" class="btn btn-primary rounded-end-3 px-3">Simpan</button>
                        </div>
                    </form>

                    <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <label class="form-label fw-bold small">Status Pembayaran</label>
                        <div class="input-group">
                            <select name="payment_status" class="form-select rounded-start-3">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Lunas</option>
                                <option value="partially_paid" {{ $order->payment_status == 'partially_paid' ? 'selected' : '' }}>Sebagian</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Gagal</option>
                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refund</option>
                            </select>
                            <button type="submit" class="btn btn-primary rounded-end-3 px-3">Simpan</button>
                        </div>
                    </form>

                    <form action="{{ route('admin.orders.update-shipping-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label class="form-label fw-bold small">Status Pengiriman</label>
                        <div class="input-group mb-3">
                            <select name="shipping_status" class="form-select rounded-start-3">
                                <option value="pending" {{ $order->shipping_status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="processing" {{ $order->shipping_status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="shipped" {{ $order->shipping_status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="delivered" {{ $order->shipping_status == 'delivered' ? 'selected' : '' }}>Terkirim</option>
                                <option value="cancelled" {{ $order->shipping_status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <button type="submit" class="btn btn-primary rounded-end-3 px-3">Simpan</button>
                        </div>

                        <div class="p-3 bg-light rounded-4 border">
                            <h6 class="fw-bold small mb-3">Informasi Pelacakan (Optional)</h6>
                            <div class="mb-2">
                                <label class="x-small text-muted fw-bold mb-1">Nama Kurir</label>
                                <input type="text" name="courier_name" class="form-control form-control-sm rounded-3" value="{{ $order->shipments->first()->courier_name ?? '' }}" placeholder="Contoh: JNE, J&T">
                            </div>
                            <div>
                                <label class="x-small text-muted fw-bold mb-1">Nomor Resi</label>
                                <input type="text" name="tracking_number" class="form-control form-control-sm rounded-3" value="{{ $order->shipments->first()->tracking_number ?? '' }}" placeholder="Masukkan resi...">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Proof Section -->
            @php $pendingPayment = $order->payments()->where('verification_status', 'pending')->latest()->first(); @endphp
            @if($pendingPayment && $pendingPayment->proof_of_payment)
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="mb-0 fw-bold text-warning"><i class="fas fa-file-invoice-dollar me-2"></i>Verifikasi Bukti Transfer</h5>
                </div>
                <div class="card-body p-4 pt-0 text-center">
                    <div class="mb-3 border rounded-4 p-2 bg-light">
                        <img src="{{ asset('storage/' . $pendingPayment->proof_of_payment) }}" class="img-fluid rounded-3" style="max-height: 400px; cursor: pointer;" onclick="window.open(this.src)">
                    </div>
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.orders.approve-payment', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">
                                <i class="fas fa-check-circle me-1"></i> Setujui Pembayaran
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-outline-danger w-100 rounded-pill fw-bold" data-bs-toggle="collapse" data-bs-target="#rejectCollapse">
                            <i class="fas fa-times-circle me-1"></i> Tolak Pembayaran
                        </button>
                        
                        <div class="collapse mt-3" id="rejectCollapse">
                            <form action="{{ route('admin.orders.reject-payment', $order) }}" method="POST" class="p-3 bg-light rounded-4 border">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3 text-start">
                                    <label class="form-label small fw-bold">Alasan Penolakan</label>
                                    <textarea name="reason" class="form-control form-control-sm rounded-3" rows="2" placeholder="Contoh: Nominal tidak sesuai, gambar tidak jelas" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill w-100 fw-bold">Kirim Penolakan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Customer Stats (Mini Card) -->
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-primary"></i>Profil Pelanggan</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 45px; height: 45px; font-size: 1.1rem;">
                            {{ strtoupper(substr($order->user->name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ $order->user->name ?? 'Guest Customer' }}</div>
                            <div class="small text-muted">{{ $order->customer_email }}</div>
                        </div>
                    </div>
                    <div class="border-top pt-3 mt-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Pesanan</span>
                            <span class="fw-bold small">{{ $order->user ? $order->user->orders()->count() : 1 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Daftar Sejak</span>
                            <span class="fw-bold small">{{ $order->user ? $order->user->created_at->format('M Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
