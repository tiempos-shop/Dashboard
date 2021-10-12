
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TiemposShop</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: 'Roboto', sans-serif;
        }
        body {

            background-image: url("images/back.jpg");
            background-size: 120% auto;
        }
        input::-webkit-input-placeholder {
            color:white;
        }
        input::-moz-placeholder {
            /* Mozilla Firefox 19+ */
            color: white;
        }
        input:-moz-placeholder {
            /* Mozilla Firefox 4 to 18 */
            color: white;
        }
        input:-ms-input-placeholder {
            /* Internet Explorer 10-11 */
            color: white;
        }
    </style>
</head>
<body>
    @if( session('message') != null )
    <div style="position: fixed; left: 0; top: 0; width: 100%; color: white; background-color: red; padding: 10px; font-weight: bold; font-size: 20px; text-align: center;">
        {{ session('message') }}
    </div>
    @endif
    <form action="{{ url('/inicio') }}" method="post">
    @csrf
        <div id="login-box">
            <img src="images/logo.png" style="height: 90px; width: 150px">
            <div class="form">
                <div class="item">
                    <input type="text" placeholder="Usuario" name="username">
                </div>
                <div class="item">
                    <input type="password" placeholder="Constraseña" name="password">
                </div>
            </div>
            <button type="submit" style="margin-bottom: 10px; margin-top: 30px;">Iniciar Sesión</button>
        </div>
    </form>
</body>
</html>