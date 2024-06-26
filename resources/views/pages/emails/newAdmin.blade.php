<!DOCTYPE html>

<!--

Copyright 2021 SendGrid
url: https://github.com/sendgrid/email-templates/blob/master/paste-templates/password-reset.html

-->

<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Email d'inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        /**
   * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
   */
        @media screen {
            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 400;
                src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'),
                    url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 700;
                src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'),
                    url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');
            }
        }

        /**
   * Avoid browser level font resizing.
   * 1. Windows Mobile
   * 2. iOS / OSX
   */
        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%;
            /* 1 */
            -webkit-text-size-adjust: 100%;
            /* 2 */
        }

        /**
   * Remove extra space added to tables and cells in Outlook.
   */
        table,
        td {
            mso-table-rspace: 0pt;
            mso-table-lspace: 0pt;
        }

        /**
   * Better fluid images in Internet Explorer.
   */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /**
   * Remove blue links for iOS devices.
   */
        a[x-apple-data-detectors] {
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
            text-decoration: none !important;
        }

        /**
   * Fix centering issues in Android 4.4.
   */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }

        body {
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /**
   * Collapse table borders to avoid space between cells.
   */
        table {
            border-collapse: collapse !important;
        }

        a {
            color: #1a82e2;
        }

        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
    </style>

</head>

<body style="background-color: #e9ecef; margin-bottom:12px;">

    <!-- start preheader -->
    <div class="preheader"
        style="display: none; max-width: 0;
      max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px;
      color: #fff; opacity: 0;">
        Nouveau merchant
    </div>
    <!-- end preheader -->

    <!-- start body -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:12px;">
        <!-- start logo -->
        <tr>
            <td align="center" bgcolor="#e9ecef">
                <a href="https://robus.home.ci/"
                    style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color:#3d4852;font-size:19px;font-weight:bold;text-decoration:none;
                    display:inline-block ; padding-top: 12px;width:10%;"
                    target="_blank">
                    <img src="https://robus.home.ci/public/dist/images/login.jpg" alt="" style="width:80%;">
                </a>
            </td>
        </tr>
        <!-- end logo -->

        <!-- start hero -->
        <tr>
            <td align="center" bgcolor="#e9ecef">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="left" bgcolor="#ffffff"
                            style="padding: 36px 24px 0;
              font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif;
              border-top: 3px solid #d4dadf;">
                            <h1
                                style="margin: 0; font-size: 16px; font-weight: 600;
                letter-spacing: -1px; line-height: 48px;">
                                Inscription nouveau merchant chez ROBUS
                                <br>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- end hero -->

        <!-- start copy block -->
        <tr>
            <td align="center" bgcolor="#e9ecef">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">

                    <!-- start copy -->
                    <tr>
                        <td align="left" bgcolor="#ffffff"
                            style="padding: 24px;
                            font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif;
                            font-size: 16px; line-height: 24px;">
                            <p style="margin: 0;">


                                {!! $message !!}.<br>

                                <br>

                            </p>
                        </td>
                    </tr>
                    <!-- end copy -->
                    <!-- end copy -->
                    <hr class="full-divider"
                        style="max-width:580px;border-right:0;border-top:0;border-bottom:1px solid #cacaca;border-left:0;margin:20px auto;clear:both;background-color:#dbdbdb;height:1px;border:none;width:100%;margin-top:0;margin-bottom:0">
                    <div class="account-alert-card-button-container"
                        style="padding-top:16px;padding-bottom:16px;padding-left:0px;padding-right:0px">
                        <table class="row"
                            style="border-spacing:0;border-collapse:collapse;vertical-align:top;text-align:left;padding:0;position:relative;width:100%;display:table">
                            <tr style="padding:0;vertical-align:top;text-align:left">
                                <th class="col-pad-left-2 col-pad-right-2"
                                    style="color:#0a0a0a;font-family:'Montserrat', Helvetica, Arial, sans-serif;font-weight:normal;padding:0;margin:0;text-align:left;font-size:16px;line-height:19px;padding-left:16px;padding-right:16px">
                                    <a href="https://robus.home.ci/robus/login" target="_blank"
                                        class="btn-primary btn-sm btn-rausch btn-full-width"
                                        style="font-family:'Montserrat', Helvetica, Arial, sans-serif;font-weight:normal;margin:0;text-align:left;line-height:1.3;color:#2199e8;text-decoration:none;background-color:#5896df;-webkit-border-radius:4px;border-radius:4px;display:block;padding:19px 24px 19px 24px;">
                                        <p class="text-center"
                                            style='font-weight:normal;padding:0;margin:0;font-size:16px;line-height:19px;text-align:center;font-family:"Montserrat", "Helvetica", Helvetica, Arial, sans-serif;color:white;margin-bottom:0px !important;'>
                                            Se connecter
                                        </p>
                                    </a>
                                </th>
                            </tr>
                        </table>
                    </div>
                    <!-- start copy -->
                    <tr>
                        <td
                            style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol'">
                            <table class="m_9153790228370883036footer" align="center" width="570" cellpadding="0"
                                cellspacing="0" role="presentation"
                                style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';margin:0 auto;padding:0;text-align:center;width:570px">
                                <tbody>
                                    <tr>
                                        <td align="center"
                                            style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';max-width:100vw;padding:32px">
                                            <p
                                                style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';line-height:1.5em;margin-top:0;color:#b0adc5;font-size:12px;text-align:center">
                                                © 2022 ROBUS. All rights reserved.</p>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <!-- end copy -->

                </table>
            </td>
        </tr>

    </table>
    <!-- end body -->

</body>

</html>
