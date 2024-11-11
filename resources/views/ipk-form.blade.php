<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung IPK</title>
</head>
<body>
    <h1>Cek IPK Berdasarkan NIM</h1>

    <!-- Form untuk input NIM -->
    <form action="/ipk" method="POST">
        @csrf
        <label for="nim">Masukkan NIM:</label>
        <input type="text" id="nim" name="nim" required>
        <button type="submit">Cek IPK</button>
    </form>

    @if (isset($ipk))
        <h2>IPK untuk NIM {{ $nim }} adalah {{ $ipk }}</h2>
    @elseif (isset($error))
        <h2>{{ $error }}</h2>
    @endif
</body>
</html>
