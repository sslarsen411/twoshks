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
    <p>Dear {{ $name  }}</p>
    <p>
        A customer, {{ $first_name }} {{ $last_name }}, just left a review
        @if( $loc_qty > 1 )
            for your business located at {{ $address }},
        @endif
        <span class="red">that was below your minimum rating</span>
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
        I have sent them an acknowledgment email on your behalf with a copy of their review.
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
