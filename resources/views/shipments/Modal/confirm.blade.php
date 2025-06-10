<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Konfirmasi Pengiriman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('shipments.confirm', $shipment) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Anda akan mengkonfirmasi pengiriman <strong>{{ $shipment->shipment_code }}</strong>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_description" class="form-label">Catatan Konfirmasi</label>
                        <textarea class="form-control" id="confirm_description" name="description" rows="3" required
                                  placeholder="Masukkan catatan konfirmasi...">Pengiriman dikonfirmasi dan siap untuk diproses</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_location" class="form-label">Lokasi Saat Ini</label>
                        <input type="text" class="form-control" id="confirm_location" name="location"
                               placeholder="Contoh: Gudang Utama Jakarta">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="send_notification" name="send_notification" checked>
                            <label class="form-check-label" for="send_notification">
                                Kirim notifikasi ke distributor
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Konfirmasi Pengiriman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
