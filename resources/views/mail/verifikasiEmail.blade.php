<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
    <title>My Daily</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <style>

html {
    position: cover;
        background-image: url('http://mydaily.diskominfo.morowalikab.go.id/aset/images/background/12.png'), url('http://mydaily.diskominfo.morowalikab.go.id/aset/images/background/13.png');
    background-position: left bottom, right top;
    background-repeat: no-repeat, no-repeat;
    background-size: 15% auto, 15% auto;
}
        @media only screen and (max-width:590px) {
            .c1 {
                background-color: white !important;
            }

            .c3a,
            .c3b {
                width: 100% !important;
            }
            html {
        position: cover;
        background-image: url('http://mydaily.diskominfo.morowalikab.go.id/aset/images/background/12.png'), url('http://mydaily.diskominfo.morowalikab.go.id/aset/images/background/13.png');
        background-position: left bottom, right top;
        background-repeat: no-repeat, no-repeat;
        background-size: 35% auto, 35% auto;
    }
        }

    </style>
</head>

<body> <div style="border-style:solid;border-width:thin;border-color:#dadce0;border-radius:8px;padding:40px 20px;position: cover;
    background-image: url('http://mydaily.diskominfo.morowalikab.go.id/aset/images/background/12.png'), url('http://mydaily.diskominfo.morowalikab.go.id/aset/images/background/13.png');
    background-position: left bottom, right top;
    background-repeat: no-repeat, no-repeat;
    background-size: 20% auto, 20% auto;"
align="center" class="m_-1412863935735743204mdv2rw">
        <img src="http://mydaily.diskominfo.morowalikab.go.id/aset/images/logo_idil.png" alt=""
            style="width: 200px;">
            <br><br><br>
        <div
            style="font-family:'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;border-bottom:thin solid #dadce0;color:rgba(0,0,0,0.87);line-height:32px;padding-bottom:8px;text-align:center;word-break:break-word">
            <div style="font-size:20px">Pendaftaran anda telah selesai, {{ $user->nama }} </div>
        </div>
        <div
            style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:rgba(0,0,0,0.87);line-height:20px;padding-top:20px;text-align:center">
            Silakan klik tombol dibawah atau <a href="{{ url('/login/verify/' . $user->verifyUser->token) }}"> disini</a> untuk verifikasi email anda, agar dapat masuk ke aplikasi.
            <div style="padding-top:32px;text-align:center">
                <a href="{{ url('/login/verify/' . $user->verifyUser->token) }}"
                    style="font-family:'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#ffd768;border-radius:5px;min-width:90px"
                    target="_blank"> <b style="color: #ef6b33;"> Verifikasi Email </b> </a>
            </div>
        </div>
        <div
            style="font-family:'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;padding-top:20px;font-size:12px;line-height:16px;color:#5f6368;letter-spacing:0.3px;text-align:center">
            Email resmi dari aplikasi My Daily<br><a
                style="font-family:'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.87);text-decoration:inherit">http://mydaily.diskominfo.morowalikab.go.id/</a>
        </div>
    </div>
</body>

</html>
