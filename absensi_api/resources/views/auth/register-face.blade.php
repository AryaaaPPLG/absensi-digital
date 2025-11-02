<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftarkan Wajah - Sistem Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">

    <div class="bg-gray-800 p-8 rounded-2xl shadow-xl w-full max-w-md text-center">
        <h1 class="text-2xl font-semibold mb-4">Daftarkan Wajah Anda</h1>
        <p class="text-sm text-gray-400 mb-6">
            Pastikan wajah Anda terlihat jelas di kamera, lalu klik <span class="font-semibold">"Ambil Foto"</span>.
        </p>

        <!-- Video Preview -->
        <div class="relative flex justify-center mb-4">
            <video id="camera" autoplay playsinline class="rounded-xl border border-gray-700 w-64 h-48 object-cover"></video>
            <canvas id="snapshot" class="hidden"></canvas>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col gap-3">
            <button id="captureBtn" class="bg-blue-600 hover:bg-blue-700 py-2 rounded-lg font-semibold transition">
                📸 Ambil Foto
            </button>
            <form id="faceForm" action="{{ route('face.register') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="face_image" id="faceImage">
                <button type="submit" class="bg-green-600 hover:bg-green-700 py-2 rounded-lg font-semibold transition">
                    ✅ Simpan Wajah
                </button>
            </form>
            <a href="{{ route('dashboard') }}" class="text-sm text-blue-400 hover:underline">Lewati untuk sekarang</a>
        </div>
    </div>

    <script>
        const video = document.getElementById('camera');
        const canvas = document.getElementById('snapshot');
        const captureBtn = document.getElementById('captureBtn');
        const faceForm = document.getElementById('faceForm');
        const faceImageInput = document.getElementById('faceImage');

        // Aktifkan kamera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                alert("Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.");
            });

        // Ambil foto wajah
        captureBtn.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataURL = canvas.toDataURL('image/png');
            faceImageInput.value = dataURL;
            faceForm.classList.remove('hidden');
            captureBtn.classList.add('hidden');

            // Hentikan kamera setelah capture
            video.srcObject.getTracks().forEach(track => track.stop());
        });
    </script>

</body>
</html>
