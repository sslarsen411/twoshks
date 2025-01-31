<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Review Thank You</title>
    <style media="all">
        /* -------------------------------------
        GLOBAL RESETS
    ------------------------------------- */

        body {
            font-family: Helvetica, sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 16px;
            line-height: 1.3;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: separate;
            mso-table-lspace: 0;
            mso-table-rspace: 0;
            width: 100%;
        }

        table td {
            font-family: Helvetica, sans-serif;
            font-size: 16px;
            vertical-align: top;
        }

        /* -------------------------------------
        BODY & CONTAINER
    ------------------------------------- */

        body {
            background-color: #f4f5f6;
            margin: 0;
            padding: 0;
        }

        .body {
            background-color: #f4f5f6;
            width: 100%;
        }

        .container {
            margin: 0 auto !important;
            max-width: 600px;
            padding: 24px 0 0;
            width: 600px;
        }

        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 600px;
            padding: 0;
        }

        /* -------------------------------------
        HEADER, FOOTER, MAIN
    ------------------------------------- */

        .main {
            background: #ffffff;
            border: 1px solid #eaebed;
            border-radius: 16px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 24px;
        }

        .footer {
            clear: both;
            padding-top: 24px;
            text-align: center;
            width: 100%;
        }

        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #9a9ea6;
            font-size: 16px;
            text-align: center;
        }

        /* -------------------------------------
        TYPOGRAPHY
    ------------------------------------- */

        p {
            font-family: Helvetica, sans-serif;
            font-size: 16px;
            font-weight: normal;
            margin: 0 0 16px;
        }

        a {
            color: #0867ec;
            text-decoration: underline;
        }

        /* -------------------------------------
        OTHER STYLES THAT MIGHT BE USEFUL
    ------------------------------------- */

        .powered-by a {
            text-decoration: none;
        }

        /* -------------------------------------
        RESPONSIVE AND MOBILE FRIENDLY STYLES
    ------------------------------------- */

        @media only screen and (max-width: 640px) {
            .main p,
            .main td,
            .main span {
                font-size: 16px !important;
            }

            .wrapper {
                padding: 8px !important;
            }

            .content {
                padding: 0 !important;
            }

            .container {
                padding: 0 !important;
                padding-top: 8px !important;
                width: 100% !important;
            }

            .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }
        }

        /* -------------------------------------
        PRESERVE THESE STYLES IN THE HEAD
    ------------------------------------- */
        .apple-link a {
            color: inherit !important;
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            text-decoration: none !important;
        }

        #MessageViewBody a {
            color: inherit;
            text-decoration: none;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit;
        }

    </style>
    <title>Review Guru</title>
</head>
<body>
<table role="presentation" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                <table role="presentation" class="main">
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper">
                            <p>{{$first_name }}, here&apos;s a copy of your review for {{ $company }}:</p>
                            <div class="review"
                                 style="width: 96%; margin-left: auto; margin-right:auto; margin-bottom:20px; padding:10px;
        background-color:#DEDEDE;">
                                {{ $review }}
                            </div>
                            <p>
                                Thank you for using the Two Shakes App.
                            </p>
                            <p>Best Regards</p>
                            <table>
                                <tr>
                                    <td>The Review Guru</td>
                                    <td><img src="https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.png"
                                             alt="Useful alt text" width="128" height="128"
                                             style="border:0; outline:none; text-decoration:none; display:block;"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- END MAIN CONTENT AREA -->
                </table>
                <!-- START FOOTER -->
                <div class="footer">
                    <table role="presentation">
                        <tr>
                            <td class="content-block">
                                <span class="apple-link">Designed by Mojo Impact LLC</span>
                                <br> Don't like these emails? <a href="#">Unsubscribe</a>.
                            </td>
                        </tr>

                    </table>
                </div>
                <!-- END FOOTER -->
                <!-- END CENTERED WHITE CONTAINER -->
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
