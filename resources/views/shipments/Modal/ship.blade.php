<!-- Modal Pengiriman -->
<div class="modal fade" id="shipModal" tabindex="-1" aria-labelledby="shipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shipModalLabel">
                    <i class="fas fa-shipping-fast me-2"></i>Proses Pengiriman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('shipments.ship', $shipment) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Pengiriman <strong>{{ $shipment->shipment_code }}</strong> akan diproses untuk dikirim
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ship_courier_id" class="form-label">Kurir <span class="text-danger">*</span></label>
                                <select class="form-select" id="ship_courier_id" name="courier_id" required>
                                    <option value="">Pilih Kurir</option>
                                    @foreach($couriers ?? [] as $courier)
                                    <option value="{{ $courier->id }}" {{ $shipment->courier_id == $courier->id ? 'selected' : '' }}>
                                        {{ $courier->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ship_service_type" class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                                <select class="form-select" id="ship_service_type" name="service_type" required>
                                    <option value="">Pilih Layanan</option>
                                    <option value="regular" {{ $shipment->service_type == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="express" {{ $shipment->service_type == 'express' ? 'selected' : '' }}>Express</option>
                                    <option value="same_day" {{ $shipment->service_type == 'same_day' ? 'selected' : '' }}>Same Day</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tracking_number" class="form-label">Nomor Resi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tracking_number" name="tracking_number"
                                       value="{{ $shipment->tracking_number }}" required placeholder="Masukkan nomor resi">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ship_estimated_delivery" class="form-label">Estimasi Tiba</label>
                                <input type="date" class="form-control" id="ship_estimated_delivery" name="estimated_delivery"
                                       value="{{ $shipment->estimated_delivery ? $shipment->estimated_delivery->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ship_delivery_cost" class="form-label">Biaya Pengiriman</label>
                                <input type="number" class="form-control" id="ship_delivery_cost" name="delivery_cost"
                                       value="{{ $shipment->delivery_cost }}" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ship_driver_name" class="form-label">Nama Driver</label>
                                <input type="text" class="form-control" id="ship_driver_name" name="driver_name"
                                       placeholder="Nama driver/kurir">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ship_description" class="form-label">Catatan Pengiriman <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ship_description" name="description" rows="3" required
                                  placeholder="Masukkan catatan pengiriman...">Paket telah diserahkan ke kurir dan dalam perjalanan ke tujuan</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="ship_location" class="form-label">Lokasi Pengiriman</label>
                        <input type="text" class="form-control" id="ship_location" name="location"
                               placeholder="Contoh: Terminal Cargo Jakarta">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ship_send_notification" name="send_notification" checked>
                                <label class="form-check-label" for="ship_send_notification">
                                    Kirim notifikasi ke distributor
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="generate_label" name="generate_label" checked>
                                <label class="form-check-label" for="generate_label">
                                    Cetak label pengiriman otomatis
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-shipping-fast me-2"></i>Proses Pengiriman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
