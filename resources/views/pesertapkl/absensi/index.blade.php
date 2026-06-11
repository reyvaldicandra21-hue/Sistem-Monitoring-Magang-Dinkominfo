@extends('layouts.pesertapkl')

@section('content')





<div class="container-fluid">

<!-- HEADER WIDGET -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft d-flex flex-column flex-md-row justify-content-between align-items-center p-4">
            <div>
                <h4 class="fw-bold mb-1">Absensi Kehadiran</h4>
                <div class="date-display" id="currentDate">Memuat tanggal...</div>
            </div>
            <div class="text-center mt-3 mt-md-0">
                <div class="clock-display" id="liveClock">00:00:00</div>
                <small class="text-success"><i class='bx bxs-circle'></i> Server Time</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

<!-- KIRI: KAMERA -->
<div class="col-lg-7">
    <div class="card-soft h-100">

        @if($absensi && in_array($absensi->status, ['izin', 'sakit']))

            {{-- ============================================================ --}}
            {{-- PANEL IZIN: Tampil jika peserta sudah mengajukan izin/sakit  --}}
            {{-- ============================================================ --}}
            <div class="izin-info-panel">
                <div class="izin-icon-wrap">
                    @if($absensi->status === 'sakit')
                        <i class='bx bx-plus-medical izin-icon'></i>
                    @else
                        <i class='bx bx-envelope-open izin-icon'></i>
                    @endif
                </div>

                <h5 class="izin-title">
                    @if($absensi->status === 'sakit')
                        Anda Mengajukan Sakit
                    @else
                        Anda Sudah Izin
                    @endif
                </h5>

                <p class="izin-subtitle">
                    Pengajuan
                    <strong>{{ $absensi->status === 'sakit' ? 'Sakit' : 'Izin' }}</strong>
                    Anda untuk hari ini telah tercatat.
                    Foto absensi tidak diperlukan.
                </p>

                @if($absensi->alasan)
                <div class="izin-reason-box">
                    <div class="izin-reason-label">
                        <i class='bx bx-comment-detail'></i> Alasan Pengajuan
                    </div>
                    <p class="izin-reason-text">{{ $absensi->alasan }}</p>
                </div>
                @endif
            </div>

        @else

            {{-- ============================================================ --}}
            {{-- PANEL NORMAL: Kamera & absensi biasa                         --}}
            {{-- ============================================================ --}}
            <h5 class="fw-bold mb-4"><i class='bx bx-camera text-primary'></i> Pengambilan Foto</h5>

            <!-- STEPPER -->
            <div class="step-indicator">
                <div class="step-item active" id="step1">
                    <div class="step-circle">1</div>
                    <span>Lokasi</span>
                </div>
                <div class="step-item" id="step2">
                    <div class="step-circle">2</div>
                    <span>Foto</span>
                </div>
                <div class="step-item" id="step3">
                    <div class="step-circle">3</div>
                    <span>Kirim</span>
                </div>
            </div>

            <!-- CAMERA FRAME -->
            <div class="camera-frame mb-4" id="cameraFrame">
                <div class="video-box text-center">
                    <video id="video" autoplay style="display:none; width: 100%; max-width: 450px; height: auto; border-radius: 8px; margin: 0 auto; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"></video>
                    <img id="preview" style="display:none; width: 100%; max-width: 450px; height: auto; border-radius: 8px; margin: 0 auto; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <div id="cameraPlaceholder" class="py-5 text-muted">
                        <i class='bx bx-video-off' style="font-size: 4rem; opacity: 0.5;"></i>
                        <p class="mt-2 mb-0">Kamera akan aktif setelah mendapat akses lokasi</p>
                    </div>
                </div>
                <canvas id="canvas" style="display:none"></canvas>
            </div>

            <!-- LOGIC BUTTONS -->
            @if(!$absensi || !$absensi->jam_masuk)
                <form method="POST" action="{{route('pesertapkl.absen.masuk')}}">
                    @csrf
                    <input type="hidden" name="foto" id="foto">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <button type="button" class="btn btn-primary w-100 py-3 fw-bold fs-5 shadow-sm" id="btnMain" onclick="handleStep()">
                        <i class='bx bx-map'></i> Dapatkan Lokasi & Mulai
                    </button>
                </form>
            @elseif(!$absensi->jam_pulang)
                <form method="POST" action="{{route('pesertapkl.absen.pulang')}}">
                    @csrf
                    <input type="hidden" name="foto" id="foto">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <button type="button" class="btn btn-warning w-100 py-3 fw-bold fs-5 shadow-sm text-dark" id="btnMain" onclick="handleStep()">
                        <i class='bx bx-map'></i> Dapatkan Lokasi & Mulai Pulang
                    </button>
                </form>
            @else
                <div class="alert alert-success text-center py-4 rounded-3 border-0 shadow-sm">
                    <i class='bx bxs-check-circle text-success' style="font-size: 4rem;"></i>
                    <h4 class="fw-bold mt-2 mb-1">Hebat!</h4>
                    <p class="mb-0">Absensi Anda hari ini sudah lengkap.</p>
                </div>
            @endif

        @endif

    </div>
</div>

<!-- KANAN: WIDGETS -->
<div class="col-lg-5">

    <!-- STATUS WIDGETS -->
    <div class="row g-3 mb-4">
        <div class="col-6">
            <div class="status-widget widget-masuk">
                <i class='bx bxs-sun text-success icon-large'></i>
                <div class="text-secondary fw-semibold mb-1">Jam Masuk</div>
                <h4 class="fw-bold text-dark mb-0">{{ $absensi->jam_masuk ?? '--:--' }}</h4>
            </div>
        </div>
        <div class="col-6">
            <div class="status-widget widget-pulang">
                <i class='bx bxs-moon text-warning icon-large'></i>
                <div class="text-secondary fw-semibold mb-1">Jam Pulang</div>
                <h4 class="fw-bold text-dark mb-0">{{ $absensi->jam_pulang ?? '--:--' }}</h4>
            </div>
        </div>
    </div>

    <!-- PANDUAN -->
    <div class="card-soft mb-4">
        <h6 class="fw-bold mb-3"><i class='bx bx-info-circle text-info'></i> Panduan Absensi</h6>
        <ul class="text-secondary ps-3 mb-0" style="line-height: 1.8;">
            <li>Pastikan Anda berada di lokasi yang diizinkan.</li>
            <li>Izinkan browser untuk mengakses <strong>Lokasi (GPS)</strong>.</li>
            <li>Izinkan browser mengakses <strong>Kamera</strong>.</li>
            <li>Posisikan wajah Anda dengan jelas di depan kamera.</li>
        </ul>
    </div>

    <!-- AKSI LAIN -->
    <div class="card-soft">
        <h6 class="fw-bold mb-3"><i class='bx bx-list-plus text-secondary'></i> Tidak bisa hadir?</h6>
        <p class="text-muted small">Jika Anda sakit atau memiliki keperluan mendesak, silakan ajukan izin resmi.</p>
        <a href="{{route('pesertapkl.absen.formizin')}}" class="btn btn-outline-secondary w-100 fw-semibold">
            <i class='bx bx-envelope'></i> Form Pengajuan Izin
        </a>
    </div>

</div>

</div>

</div>

@endsection

@section('scripts')
<script>
// ================= JAM & TANGGAL =================
function updateClock() {
    const now = new Date();

    // Format Jam
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('liveClock').innerText = `${hours}:${minutes}:${seconds}`;

    // Format Tanggal
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('currentDate').innerText = now.toLocaleDateString('id-ID', options);
}
setInterval(updateClock, 1000);
updateClock(); // Inisialisasi awal

// ================= VARIABEL KAMERA =================
let video = document.getElementById('video');
let canvas = document.getElementById('canvas');
let preview = document.getElementById('preview');
let btn = document.getElementById('btnMain');
let placeholder = document.getElementById('cameraPlaceholder');
let cameraFrame = document.getElementById('cameraFrame');

let stream = null;
let step = 1;

// ================= STEPPER LOGIC =================
function updateStepper(activeStep) {
    document.getElementById('step1').classList.remove('active');
    document.getElementById('step2').classList.remove('active');
    document.getElementById('step3').classList.remove('active');

    for(let i=1; i<=activeStep; i++) {
        document.getElementById('step' + i).classList.add('active');
    }
}

function handleStep(){
    if(!btn) return; // Jika tombol tidak ada (sudah absen lengkap)

    if(step === 1){
        ambilLokasi();
    }
    else if(step === 2){
        ambilFoto();
    }
    else if(step === 3){
        submitAbsen();
    }
}

// ================= 1. GPS =================
function ambilLokasi(){
    if(!navigator.geolocation){
        alert("Browser tidak mendukung GPS");
        return;
    }

    btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> Mencari Lokasi...";
    btn.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function(position){
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;

            btn.disabled = false;
            updateStepper(2);
            startCamera();
        },
        function(){
            alert("Harap aktifkan GPS terlebih dahulu!");
            btn.innerHTML = "<i class='bx bx-map'></i> Coba Cari Lokasi Lagi";
            btn.disabled = false;
        }
    );
}

// ================= 2. CAMERA =================
function startCamera(){
    navigator.mediaDevices.getUserMedia({video:true})
    .then(function(s){
        stream = s;
        video.srcObject = stream;

        placeholder.style.display = 'none';
        video.style.display = 'block';
        preview.style.display = 'none';

        cameraFrame.classList.add('active');

        btn.innerHTML = "<i class='bx bx-camera'></i> Ambil Foto";
        btn.classList.remove('btn-primary','btn-warning');
        btn.classList.add('btn-info', 'text-white');

        step = 2;
    })
    .catch(function(){
        alert('Kamera tidak dapat diakses. Mohon izinkan akses kamera.');
    });
}

// ================= 3. FOTO =================
function ambilFoto(){
    let targetWidth = 640;
    let scale = targetWidth / video.videoWidth;
    let targetHeight = video.videoHeight * scale;

    if (scale >= 1) {
        targetWidth = video.videoWidth;
        targetHeight = video.videoHeight;
    }

    canvas.width = targetWidth;
    canvas.height = targetHeight;

    let ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, targetWidth, targetHeight);

    let data = canvas.toDataURL('image/jpeg', 0.8);
    document.getElementById('foto').value = data;

    // preview
    preview.src = data;
    preview.style.display = 'block';
    video.style.display = 'none';

    cameraFrame.classList.remove('active');

    stopCamera();
    updateStepper(3);

    btn.innerHTML = "<i class='bx bx-send'></i> Simpan Absensi";
    btn.classList.remove('btn-info');
    btn.classList.add('btn-success');

    step = 3;
}

// ================= 4. SUBMIT =================
function submitAbsen(){
    btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> Menyimpan...";
    btn.disabled = true;
    btn.closest('form').submit();
}

// ================= STOP CAMERA =================
function stopCamera(){
    if(stream){
        stream.getTracks().forEach(track => track.stop());
        video.srcObject = null;
    }
}
</script>
@endsection
