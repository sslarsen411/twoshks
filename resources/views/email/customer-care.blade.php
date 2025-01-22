<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        div {
            margin-left: 2rem;
        }

        p, .review {
            font-family: sans-serif;
            font-size: 14px;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
<div>
    <table role="presentation">
        <tr>
            <td style=" vertical-align: center">
                <img src="https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.webp" width="88" height="128"
                     alt="The Rave Review Guru"
                     style="display: inline; outline: 0; line-height: 100%; width: 160px; height: auto; max-width: 100%; border: 0;"/>
            </td>
            <td style="vertical-align: center">
                <span
                    style="font-size:2rem; font-family: ' Roboto', Arial, Helvetica, sans-serif; ">The Review Guru</span>
            </td>
        </tr>
    </table>
    <p>{{$first_name }}, {{ $company }} has been notified about the concerns you expressed on the Two Shakes App.</p>
    <p>Below is a copy of what you wrote:</p>
    <div class="review"
         style="width: 96%; margin-left: auto; margin-right:auto; margin-bottom:20px; padding:10px;
        background-color:#DEDEDE;">
        {{ $review }}
    </div>
    <p>
        Thank you for using the Two Shakes App.
    </p>
    <p>
        The Review Guru
    </p>
</div>
</body>
</html>
