<body style="background-color: #f5f8fa; font-family: 'Raleway', sans-serif; padding: 50px 0;">

<div class="email-wrapper" style="font-family: sans-serif; text-align: center; width: 640px; background-color: white; padding: 15px; margin: 0 auto;">
    <img src="http://emrabebe.com/images/logo.png" height="30px" alt="emrabebe.com">
    <h3>Hallo {{ $name }},</h3>
    <p>du hast dich auf emrabebe.com registriert, jetzt must du nur noch auf den button unten klicken</p>
    <br><br>
    <a href="{!! url('verifyemail'); !!}/{{$email_token}}" style="background-color: #00b1b3; border-width: 2px; text-decoration: none; text-transform: uppercase;font-weight: 700; padding: 11px 24px; margin: 10px; font-family: 'Raleway', sans-serif; color: #fff;">Konto Jetzt aktivieren</a>
    <br><br><br>
    <small>Wenn du aus irgendeinem Grund nicht auf den Button klicken kannst, kopiere den unten stehenden Link in deinen Browser</small>
    <br>
    <span>{!! url('verifyemail'); !!}/{{$email_token}}</span>
    <br><br>
</div>
<p style="
    text-align: center;
    width: 600px;
    font-size: 10px;
    margin: 15px auto;">
    Du erhältst diese Nachricht automatisiert, weil du ein neues Passwort angefordert hast.
    © emrabebe.com, E accounts@emrabebe.com,
    Impressum Datenschutz AGB
</p>

</body>
