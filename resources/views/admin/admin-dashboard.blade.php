<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Admin Dashboard · NurSync</title>
      <link rel="icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-50">

  <main class="min-h-screen flex">
    {{-- Sidebar --}}
    @include('partials.admin-sidebar', ['active' => 'dashboard'])

    {{-- Main contents --}}
    @include('partials.admin-dashboard-main')
  </main>

  {{-- Shared footer --}}
  @include('partials.admin-footer')

  <script src="https://unpkg.com/lucide@latest"></script>
  <script> lucide.createIcons(); </script>
</body>
</html>
