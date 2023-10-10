<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scan Qr Satria</title>
    <link rel="stylesheet" href="/vuejs/style.css">
</head>

<body>
    <!-- Vue Js App Code -->
    <div id="app">
        <div class="header" style="margin-bottom: 20px;">{{ header }}</div>
        <div class="section" style="width: 500px; margin: auto;">
            <div class="justify-content-center">
                <scanner v-bind:qrbox="250" v-bind:fps="10" style="width: 300px;">
                </scanner>
            </div>
        </div>
        <div class="footer" style="margin-bottom: 20px;">Result: {{ result }}</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        Vue.component('scanner', {
            props: {
                qrbox: Number,
                fps: Number,
            },
            template: `<div id="qr-code-full-region"></div>`,
            mounted: function() {
                var $this = this;
                var config = {
                    fps: this.fps ? this.fps : 10
                };
                if (this.qrbox) {
                    config['qrbox'] = this.qrbox;
                }

                function onScanSuccess(decodedText, decodedResult) {
                    // Redirect to detail.php with the product ID (resi in this case)
                    window.location.href = '?url=detail&resi=' +
                        encodeURIComponent(decodedText);
                }

                var html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-code-full-region", config);
                html5QrcodeScanner.render(onScanSuccess);
            }
        });

        var app = new Vue({
            el: '#app',
            data: {
                header: 'Scan QR ',
                result: ''
            },
        });
    </script>
</body>

</html>