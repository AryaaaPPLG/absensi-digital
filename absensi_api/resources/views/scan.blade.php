<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Digital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .camera-frame {
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        
        .status-active {
            background-color: #10b981;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .btn-primary {
            background-color: #3b82f6;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }
        
        .btn-secondary {
            background-color: #6b7280;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
            transform: translateY(-2px);
        }
        
        .result-card {
            border-left: 4px solid #3b82f6;
            background-color: white;
        }
        
        .attendance-success {
            border-left-color: #10b981;
        }
        
        .attendance-error {
            border-left-color: #ef4444;
        }
        
        .face-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            border: 2px dashed #3b82f6;
            border-radius: 8px;
            pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">
    <!-- Main Container -->
    <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Sistem Absensi Digital</h1>
                    <p class="text-blue-100 mt-1">Absensi berbasis pengenalan wajah</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center">
                    <span class="status-indicator status-active"></span>
                    <span class="text-sm">Sistem Aktif</span>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="p-6">
            <!-- Camera Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Kamera Absensi</h2>
                <div class="camera-frame rounded-lg overflow-hidden bg-gray-900 relative mx-auto" style="max-width: 640px;">
                    <video id="camera" autoplay playsinline class="w-full h-auto"></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    
                    <!-- Face detection overlay -->
                    <div class="face-overlay"></div>
                    
                    <!-- Camera status -->
                    <div class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                        <i class="fas fa-circle text-green-500 mr-1"></i> Kamera aktif
                    </div>
                </div>
                
                <!-- Camera instructions -->
                <div class="mt-4 text-center text-sm text-gray-600">
                    <p>Pastikan wajah Anda terlihat jelas dalam frame dan pencahayaan cukup</p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-8">
                <button id="registerBtn" class="btn-primary text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i> Daftarkan Wajah
                </button>
                <button id="scanBtn" class="btn-secondary text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center">
                    <i class="fas fa-camera mr-2"></i> Ambil Absensi
                </button>
            </div>
            
            <!-- Result Section -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Hasil Absensi</h2>
                <div id="result" class="result-card rounded-lg p-4 shadow-sm">
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>Hasil absensi akan muncul di sini setelah proses pemindaian</p>
                    </div>
                </div>
            </div>
            
            <!-- Information Section -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-medium text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i> Petunjuk Penggunaan
                </h3>
                <ul class="text-sm text-blue-700 list-disc pl-5 space-y-1">
                    <li>Gunakan tombol "Daftarkan Wajah" untuk pertama kali menggunakan sistem</li>
                    <li>Gunakan tombol "Ambil Absensi" untuk melakukan absensi harian</li>
                    <li>Pastikan wajah Anda berada dalam area yang ditandai</li>
                    <li>Pastikan pencahayaan cukup untuk pengenalan wajah yang optimal</li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-100 border-t border-gray-200 p-4 text-center text-sm text-gray-600">
            <p>Sistem Absensi Digital &copy; 2023 - Divisi Teknologi Informasi</p>
        </div>
    </div>

  <script>
    const video = document.getElementById("camera");
const canvas = document.getElementById("canvas");
const result = document.getElementById("result");

async function startCamera() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
  } catch (err) {
    alert("Gagal mengakses kamera: " + err.message);
  }
}

function captureImage() {
  const context = canvas.getContext("2d");
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  context.drawImage(video, 0, 0, canvas.width, canvas.height);
  return canvas.toDataURL("image/jpeg");
}

async function registerFace() {
  const username = prompt("Masukkan nama pengguna untuk registrasi:");
  if (!username) return alert("Nama pengguna wajib diisi!");

  const imageData = captureImage();

  const formData = new FormData();
  formData.append("username", username);
  formData.append("image", dataURLtoBlob(imageData), "face.jpg");

  try {
    const response = await fetch("http://127.0.0.1:5000/api/register-face", {
      method: "POST",
      body: formData,
    });
    const data = await response.json();
    result.textContent = JSON.stringify(data, null, 2);
  } catch (err) {
    result.textContent = "Error: " + err.message;
  }
}

async function scanFace() {
  const imageData = captureImage();

  try {
    const response = await fetch("http://127.0.0.1:5000/api/recognize-face", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ image: imageData }),
    });

    const data = await response.json();
    result.textContent = JSON.stringify(data, null, 2);
  } catch (err) {
    result.textContent = "Error: " + err.message;
  }
}

function dataURLtoBlob(dataURL) {
  const byteString = atob(dataURL.split(",")[1]);
  const mimeString = dataURL.split(",")[0].split(":")[1].split(";")[0];
  const ab = new ArrayBuffer(byteString.length);
  const ia = new Uint8Array(ab);
  for (let i = 0; i < byteString.length; i++) {
    ia[i] = byteString.charCodeAt(i);
  }
  return new Blob([ab], { type: mimeString });
}

document.getElementById("registerBtn").addEventListener("click", registerFace);
document.getElementById("scanBtn").addEventListener("click", scanFace);

startCamera();

  </script>
</body>
</html>
