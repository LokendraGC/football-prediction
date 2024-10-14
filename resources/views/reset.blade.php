<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>

<body>
    <h1>You have requested to reset your password</h1>
    <hr>
    <p>We can not simply send your old password. A unique link to reset your password has been generated for you. To
        reset Password click the following link</p>
    <h1><a href="http//127.0.0.1:3000/api/user/reset/{{ $token }}">Reset Password Link</a></h1>
</body>

</html>
