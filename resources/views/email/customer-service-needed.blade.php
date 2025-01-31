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

        h2, p, .review {
            font-family: sans-serif;
            font-size: 14px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 18px;
        }


        .red {
            color: red;
        }
    </style>
    <title>Review Guru</title>
</head>
<body>
<div>
    <table role="presentation">
        <tr>
            <td style=" vertical-align: center">
                <img src="https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.png" width="128" height="128"
                     alt="The Rave Review Guru"
                     style="display: inline; outline: 0; line-height: 100%; width: 160px; height: auto; max-width: 100%; border: 0;"/>
            </td>
            <td style="vertical-align: center">
                <span
                    style="font-size:2rem; font-family: ' Roboto', Arial, Helvetica, sans-serif; ">The Review Guru</span>
            </td>
        </tr>
    </table>
    <p>
        Your customer, {{ $first_name }} {{ $last_name }}, just left a review <span class="red">below your minimum rating</span>
        threshold of {{ $min_rate }}.
    </p>
    <p>
        The rating they gave you was <span class="red">{{ $rating }}</span>. They wrote:
    </p>
    <div class="review"
         style="width: 90%; margin-left: auto; margin-right:auto; margin-bottom:20px; padding:10px;
        background-color:#DEDEDE;">
        {{ $review }}
    </div>
    <h2>Contact Info</h2>
    <p style="margin-left:10px">
        Phone: {{ $phone }}
    <p style="margin-left:10px">
        Email: {{ $email }}.
    </p>
    <p>
        You can also reach out to them through the <a href="https://portal.twoshakes.app/" target="blank">Two Shakes
            Admin Portal</a> under either the <strong>Customers</strong> or <strong>Reviews</strong> pages.
    </p>
    <p>
        I have sent them an acknowledgment email with a copy of their review.
    </p>
    <p>
        I urge you to contact them as soon as possible before they post anything online to keep this a private matter.
    </p>
    <p>
        The Review Guru
    </p>
</div>
</body>
</html>
