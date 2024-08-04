<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page | User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            position: relative;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            z-index: 1;
            position: relative;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            z-index: 0;
            position: relative;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        #error {
            list-style: none;
            padding: 0;
            margin: 0;
            color: red;
            text-align: center;
        }
        #error li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <h1>Register A User</h1>
    <form id="register_form">
        <label for="name">Your Name:</label>
        <input type="text" name="name" placeholder="Enter name" required><br><br>
        <label for="email">Your Email:</label>
        <input type="email" name="email" placeholder="Enter email" required><br><br>
        <label for="password">Your Password:</label>
        <input type="password" name="password" placeholder="Enter password" required><br><br>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" placeholder="Enter confirm password" required><br><br>
        <button type="submit">Submit</button>
    </form>
    <ul id="error"></ul>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js" integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function(){
            let DomainName = window.location.origin;
            $('#register_form').submit(function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $('#error').empty();
                $.ajax({
                    url: `${DomainName}/api/register`,
                    type: 'POST',
                    data: formData,
                    success: function(res){
                        let errors = res.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                if (key == 'password') {
                                    if (value.length > 1) {
                                        $('#error').append(`<li>${value[0]}</li><li>${value[1]}</li>`);
                                    } else {
                                        if (value[0].includes('password confirmation')) {
                                            $('#error').append(`<li>${value}</li>`);
                                        } else {
                                            $('#error').append(`<li>${value}</li>`);
                                        }
                                    }
                                } else {
                                    $('#error').append(`<li>${value}</li>`);
                                }
                            });
                        } else {
                            $('#register_form')[0].reset();
                            console.log(res.msg);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
