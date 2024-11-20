<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>

<body>
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="box container">
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ old('email') }}">
    </div>
</body>

</html>
