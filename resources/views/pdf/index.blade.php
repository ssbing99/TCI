<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Lesson PDF</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        body {
            font-weight: 400;
            line-height: 1.5;
            text-align: left;
        }
        em {
            font-style: normal;
        }
        h1, h2, h3, h4, span, p, div {
            line-height: 1.6;
            font-family: DejaVu Sans;
        }
        h2 {
            font-size: 20px;
        }
        h4 {
            margin-bottom: 5px;
        }
        p {
            line-height: 200%;
            margin: 15px 0;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
        }
        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid black;
        }

    </style>
</head>
<body>
<div style="display: block;clear: both">
    <div style="float: left; width:250pt;">
        <img class="img-rounded" height="50px"
             src="{{ public_path('assets_new/images/tci-logo.jpg') }}">
    </div>
</div>
<div style="display: inline-block;clear: both;width: 100%;">
    <div style="width:100%; float:left;">
        <br/>
        <h4>{{ $lesson->title }}</h4>
        <br/>
{{--        <h5>Sub Title</h5>--}}
    </div>
    <hr/>

    {!! $lesson->full_text !!}


</div>

</body>
</html>
