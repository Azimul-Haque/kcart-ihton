<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            overflow: hidden;
        }
        /* ইউজার এক্সপেরিয়েন্স সুন্দর রাখতে উইজেটটি সেন্টারে এলাইন করা */
        .cf-turnstile-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>

    <div class="cf-turnstile-container">
        <div class="cf-turnstile" 
             data-sitekey="0x4AAAAAACIxvQroHjZKiX0E" 
             data-callback="javascriptCallback"
             data-theme="light"></div>
    </div>

    <script>
        function javascriptCallback(token) {
            // ফ্লাটার অ্যাপের InAppWebView-এর addJavaScriptHandler-কে টোকেনটি পুশ করার মেথড
            if (window.flutter_inappwebview) {
                window.flutter_inappwebview.callHandler('onTurnstileSuccess', token);
            } else {
                console.log("Flutter interface not found. Token: " + token);
            }
        }
    </script>

</body>
</html>