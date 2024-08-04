<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forget Password</title>
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
            text-align: center;
        }
        .header {
            background: #f4f4f4;
            color: #000;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        p {
            margin: 10px 0;
            font-size: 16px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="email"] {
            width: calc(100% - 22px);
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: block;
        }
        button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #333;
        }
        .error, .success {
            color: red;
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }
        .success {
            color: green;
        }
        #error li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Forget Password?</h1>
        </div>

        <p>Enter Your Registered Email</p>
        <form id="forget_password">
            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Enter email" required><br>
            <button type="submit">Send Reset Link</button>
        </form>
        <ul id="error" class="error"></ul>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function(){
            let DomainName = window.location.origin;
            $('#forget_password').submit(function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $('#error').empty();
                $.ajax({
                    url: `${DomainName}/api/forget-password`,
                    type: 'POST',
                    data: formData,
                    success: function(res){
                        if (res.success) {
                            $('#error').append(`<li class="success">${res.msg}</li>`);
                        } else {
                            $('#error').append(`<li class="error">${res.msg}</li>`);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
