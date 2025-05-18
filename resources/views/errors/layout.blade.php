<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 36px;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title">
                    @yield('message')
                    <button class="back-button" onclick="goBack()">Go Back</button>

<script>
  function goBack() {
    // Jika document.referrer ada dan berasal dari domain yang sama, gunakan itu
    if (document.referrer && document.referrer.indexOf(window.location.hostname) !== -1) {
      window.location.href = document.referrer;
    } else {
      // Jika tidak, alihkan ke homepage atau halaman yang aman
      window.location.href = '/';
    }
  }
</script>

                </div>
                
            </div>
        </div>
    </body>
</html>
