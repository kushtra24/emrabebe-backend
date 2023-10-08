
<body style="background-color: #f5f8fa; font-family: 'Raleway', sans-serif; padding: 50px 0">

<div class="email-wrapper" style="font-family: sans-serif; text-align: center; width: 640px">
    <img src="http://emrabebe.com/images/logo.png" height="30px" alt="emrabebe.com">
    <h3>Ke Harruar Fjalkalimin {{ $name }}</h3>
    <p>Mos u shqetëso, problemi do të zgjidhet shpejt! Me linkun e mëposhtme thjesht mund të zgjidhesh një fjalëkalim të ri për llogarinë tuaj ne emrabebe.com:</p>
    <a href="{!! url('al/auth/resetPassword?reset='); !!}{{$random}}" style="background-color: #00b1b3; border-width: 2px; text-decoration: none; text-transform: uppercase;font-weight: 700; padding: 11px 24px; margin: 10px; font-family: 'Raleway', sans-serif; color: #fff;">Verifiko tani</a>
    <br>
    <br>
    <small>Nëse për çfardo arsye nuk mund ta klikosh butonin, kopjoje linkun e më poshtëm në shfletuesin tënd</small>
    <br>
    <span>http://localhost:3000/al/auth/resetPassword?reset={{$random}}</span>
    {{--    {{url('/verifyemail/'.$email_token)}}--}}
    <br><br>
    <small>
        Mesazhi dërgohet automatikisht sepse ke kërkuar një fjalëkalim të ri.
        © emrabebe.com, E accounts@emrabebe.com,
        Impressum Datenschutz AGB
    </small>
</div>

</body>
