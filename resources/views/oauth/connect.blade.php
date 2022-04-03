<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  <script>
    window.opener.postMessage(
      {
        provider: "{{ $provider }}",
        connected: "{{ $connected }}"
      },

      "{{ route('spa.register') }}"
    )

    window.close()
  </script>
</head>
<body>
</body>
</html>
