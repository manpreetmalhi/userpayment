<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$data['title']}}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; color: #333;">

    <div style="background: #fff; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); padding: 20px; max-width: 600px; margin: 0 auto;">
        <h1 style="font-size: 24px; color: #333; margin-bottom: 20px;">User Payment Portal</h1>
        <p style="font-size: 16px; color: #555; margin-bottom: 20px; line-height: 1.5;">
            Dear {{$data['payer_name']}},
        </p>
        <!-- <p><strong>Subject:</strong> {{$data['title']}}</p> -->
        <p style="font-size: 16px; color: #555; margin-bottom: 20px; line-height: 1.5;">{{$data['body']}}</p>
        <p style="font-size: 16px; color: #555; margin-bottom: 20px; line-height: 1.5;">Regards,</p>
        <p style="font-size: 16px; color: #555; margin-bottom: 20px; line-height: 1.5;">UserPayPortal Team</p>
    </div>
</body>
</html>
