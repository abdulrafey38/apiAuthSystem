Hello {{$email_data['name']}}
<br><br>
Welcome to My website
<br>
Please click the email below to verify your email & activaye your account
<br><br>
<a href="http://127.0.0.1:8000/api/verify?code={{$email_data['verification_code']}}">Click Here To verify your Email Address</a>
<br><br>
Thank You
<br>
Nextbridge