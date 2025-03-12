<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>QR Code Scanner / Reader</title>
</head>

<body>
    <div class="container">
        <h1>Scan QR Codes</h1>
        <div class="section">
            <div id="my-qr-reader"></div>
            <div class="scan-history">
                <h2>Scan History</h2>
                <table id="scan-results">
                    <thead>
                        <tr>
                            <th>Scanned QR Code</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Scan results will be appended here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>function domReady(fn) {
        if (
            document.readyState === "complete" ||
            document.readyState === "interactive"
        ) {
            setTimeout(fn, 1000);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }
    
    domReady(function () {
        // If found your QR code
        function onScanSuccess(decodeText, decodeResult) {
            // Create a new row for the scan result
            const tableBody = document.getElementById("scan-results").getElementsByTagName("tbody")[0];
            const newRow = tableBody.insertRow();
    
            // Insert cells for the scanned QR code and timestamp
            const qrCodeCell = newRow.insertCell(0);
            const timestampCell = newRow.insertCell(1);
    
            qrCodeCell.textContent = decodeText;
            timestampCell.textContent = new Date().toLocaleString(); // Current timestamp
    
            // Optionally, alert the user
            alert("You scanned: " + decodeText);
        }
    
        let htmlscanner = new Html5QrcodeScanner(
            "my-qr-reader",
            { fps: 10, qrbox: 250 }
        );
        htmlscanner.render(onScanSuccess);
    }); </script>
</body>

</html>