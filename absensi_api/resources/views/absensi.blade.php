<!DOCTYPE html>
<html>
<head>
  <title>Absensi Wajah</title>
</head>
<body>
  <h1>Scan Wajah</h1>
  <form id="formAbsensi" enctype="multipart/form-data">
    <input type="file" name="image" id="image" required>
    <button type="submit">Kirim</button>
  </form>

  <pre id="result"></pre>

  <script>
    document.getElementById('formAbsensi').addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData();
      formData.append('image', document.getElementById('image').files[0]);
      
      const response = await fetch('/api/attendance/face', {
        method: 'POST',
        body: formData
      });
      
      const result = await response.json();
      document.getElementById('result').textContent = JSON.stringify(result, null, 2);
    });
  </script>
</body>
</html>
