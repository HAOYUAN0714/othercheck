<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>WidgetLoader</title>
    <script data-autoInit="true" type="text/javascript"
            src="https://cs.betradar.com/ls/widgets/?/inplaymatrix/{{ $languageCode }}/America:Santo_Domingo/widgetloader/widgets"></script>
    <script> SRLive.addWidget({
            name: 'widgets.lmts',
            config: {
                container: '.visualization_wrap',
                showTitle: false,
                matchId: '{{ $eventId }}',
                sidebarLayout: 'bottom',
                pitchCrowd: true,
                showScoreboard: false,
                showStats: false,
                collapse_enabled: false,
                showMomentum: false,
                showMomentum2: false
            }
        });
    </script>
    <style>
        body {
            padding: 0;
            margin: 0;
            background: #000;
        }

        .visualization_wrap {

            padding: 0;
            margin: 0;
            background: #000;
        }

        .div {
            -webkit-transform: translateZ(0px);
        }
    </style>
</head>
<body>
<div class="visualization_wrap"> <!--replace with bettrdar widget: starts-->
    <br/>
{{--    <img src="loading.gif" width="50px" height="50px" style="display:block;margin: 0 auto;">--}}
    <br/><br/> <!--replace with bettrdar widget: ends--> </div>
</body>
</html>
