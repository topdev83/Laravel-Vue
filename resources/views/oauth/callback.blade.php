<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  <script>
    if (window.opener) {
      window.opener.postMessage(
        {
          token: "{{ $token }}",
          baseUrl: "{{ $baseUrl }}"
        },

        "/"
      )
      window.close()
    } else {
      window.location.href = '//{{ $baseUrl }}/token/{{ $token }}'
    }
  </script>
</head>
<body>
</body>
</html>
