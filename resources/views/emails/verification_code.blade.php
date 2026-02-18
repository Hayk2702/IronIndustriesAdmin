<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Verification Code</title>
    <style type="text/css">
        body {
            margin: 0;
            background-color: #fff;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
        }

        table {
            border-spacing: 0;
        }

        td {
            padding: 0;
        }

        img {
            border: 0;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            padding-bottom: 60px;
        }

        .main {
            margin: 0 auto;
            max-width: 640px;
            border-spacing: 0;
            font-family: sans-serif;
            padding-top: 80px;
        }
    </style>
</head>
<body>
    <center class="wrapper">
        <table class="main" width="100%">

            <!-- TOP LOGO SECTION -->
            <tr>
                <td align="center">
                    <table width="100%" style="background-color: #1C1F26; color: #fff; padding: 30px;">
                        <tr>
                            <td align="left" style="padding: 10px 20px; font-size: 20px; font-weight: 400; color: #fff;">
                                Welcome to ITR.Parking
                            </td>
                            <td align="right" style="padding: 10px 20px;">
                                <img src="{{asset('images/login/email-logo.png')}}" alt="Logo" width="137">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- MAIN SECTION -->
            <tr>
                <td align="center" style="height: 590px; background: #111318; color: #fff; padding-bottom: 80px;">
                    <table width="100%" height="450px">
                        <tr>
                            <td align="center">
                                <h1 style="color: #FFF; font-size: 24px; line-height: 36px;"> Dear {{ $name }}</h1>
                            </td>
                        </tr>
                        
                        

                        <tr>
                            <td align="center">
                                <table width="216px">
                                    <tr>
                                        <td align="center" style="padding-bottom: 30px;">
                                            <p style="color: #7B7E83; font-size: 18px; line-height: 27px; font-weight: 700;">Your verify code:</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="background-color: #80F17E; border-radius: 10px; padding: 30px;">
                                            <strong style="color: #111318; font-size: 40px; line-height: 60px; letter-spacing: 10px;">{{ $verificationCode }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="center">
                                <table width="320px">
                                    <p style="text-align: center; color: #FFF;">If you have not submitted the application, please inform the system's secure service center by phone number 010-58-59-80.</p>
                                </table>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>

            <!-- FOOTER -->
            <tr>
                <td align="center" style="padding: 14px 0; background-color: #1C1F26;">
                    <p style="margin: 0; font-size: 16px; color: #FFF;"><b>ITR.Parking</b></p>
                    <p style="margin: 0; font-size: 16px; color: #828488;">Copyright © 2025</p>
                </td>
            </tr>

        </table>
    </center>
</body>
</html>
