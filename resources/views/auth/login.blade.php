<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Log In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .header {
            background: #ffffff; /* White background for simplicity */
            color: #000000; /* Black text color */
            padding: 15px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            font-size: 24px; /* Adjusted font size */
            font-weight: 700; /* Bold header */
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="email"], input[type="password"] {
            width: calc(100% - 22px);
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: block;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        p {
            margin: 10px 0;
            text-align: center;
        }
        p a {
            text-decoration: none;
        }
        .register-link {
            color: green;
            font-weight: 600;
        }
        .forget-password-link {
            color: red;
        }
        #error {
            list-style: none;
            padding: 0;
            margin: 10px 0;
            color: red;
            text-align: center;
        }
        #error li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Log In</h1>
        </div>
        <form id="login_form">
            <label for="email">Your Email:</label>
            <input type="email" name="email" placeholder="Enter email" required><br>
            <label for="password">Your Password:</label>
            <input type="password" name="password" placeholder="Enter password" required><br>
            <button type="submit">Submit</button>
        </form>
        <p>Not a User? <a href="{{route('register')}}" class="register-link">Register here</a></p>
        <p><a href="{{route('forget-password')}}" class="forget-password-link">Forget Password?</a></p>
        <p>password: 12345678</p>
        <ul id="error"></ul>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js" integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function(){
            let DomainName = window.location.origin;
            $('#login_form').submit(function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $('#error').empty();
                $.ajax({
                    url: `${DomainName}/api/login`,
                    type: 'POST',
                    data: formData,
                    success: function(res){
                        if (res.success == false) {
                            $('#error').append(`<li>${res.msg}</li>`);
                        } else if (res.success == true) {
                            $('#error').append(`<li>${res.msg}</li>`);
                            localStorage.setItem("token", `${res.token_type} ${res.token}`);
                            localStorage.setItem("token_expires", `${res.expires_in}`);
                            window.open('/profile', '_self');
                        } else {
                            $.each(res, function(key, value) {
                                $('#error').append(`<li>${value}</li>`);
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
