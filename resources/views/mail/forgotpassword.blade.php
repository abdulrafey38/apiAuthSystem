Hello {{$email_data['name']}}
<br><br>
Welcome to My website
<br>
Please Click the link below to Reset your Password
<br><br>
<a href="http://127.0.0.1:8000/api/password/areset?code={{$email_data['verification_code']}}&email={{ $email_data['email'] }}">Click Here To Reset Your Password</a>
<br><br>
Thank You
<br>
Nextbridge
