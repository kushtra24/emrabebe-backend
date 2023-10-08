
<body style="background-color: #f5f8fa; font-family: 'Raleway', sans-serif; padding: 50px 0;">

<div class="email-wrapper" style="font-family: sans-serif; text-align: center; width: 640px; background-color: white; padding: 15px; margin: 0 auto;">
    <img src="http://emrabebe.com/images/logo.png" height="30px" alt="emrabebe.com">
    <h3>Forgotten your password {{$name}}?</h3>
    <p>Don't worry, the problem will be solved quickly! With the following link you can simply choose a new password for your emrabebe.com account:</p>
    <a href="{!! url('auth/resetPassword?reset='); !!}{{$random}}" style="background-color: #00b1b3; border-width: 2px; text-decoration: none; text-transform: uppercase;font-weight: 700; padding: 11px 24px; margin: 10px; font-family: 'Raleway', sans-serif; color: #fff;">Verify now</a>
    <br>
    <br>
    <small>If for any reason you can not click the button, copy the link below into your browser</small>
    <br>
    <span>{!! url('auth/resetPassword?reset='); !!}{{$random}}</span>
    <br>
    <br>
</div>

<p style="
    text-align: center;
    width: 600px;
    font-size: 10px;
    margin: 15px auto;">
    You are receiving this message automatically because you have requested a new password.
    © emrabebe.com, E accounts@emrabebe.com,
    Impressum Datenschutz AGB
</p>

</body>
