<body style="background-color: #f5f8fa; font-family: 'Raleway', sans-serif; padding: 50px 0;">

<div class="email-wrapper" style="font-family: sans-serif; text-align: center; width: 640px; background-color: white; padding: 15px; margin: 0 auto;">
    <img src="http://emrabebe.com/images/logo.png" height="30px" alt="emrabebe.com">
    <h3>Përshendetje {{ $name }},</h3>
    <p>Je regjistruar ne emrabebe.com, gjithcka që duhet të bësh tani është të shtypësh në buttonin më poshtë</p>
    <br><br>
    <a href="{!! url('verifyemail'); !!}/{{$email_token}}" style="background-color: #00b1b3; border-width: 2px; text-decoration: none; text-transform: uppercase;font-weight: 700; padding: 11px 24px; margin: 10px; font-family: 'Raleway', sans-serif; color: #fff;">Aktivizo Llogarinë</a>
    <br><br><br>
    <small>Nëse për çfardo arsye nuk mund ta klikosh butonin, kopjoje linkun e më poshtëm në shfletuesin tënd</small>
    <br>
    <span>{!! url('verifyemail'); !!}/{{$email_token}}</span>
    <br><br>
</div>
<p style="
    text-align: center;
    width: 600px;
    font-size: 10px;
    margin: 15px auto;">
    Mesazhi dërgohet automatikisht sepse ke kërkuar një fjalëkalim të ri.
    © emrabebe.com, E info@emrabebe.com,
    Impressum Datenschutz AGB
</p>

</body>
