<script>
    const myHeaders = new Headers();
    myHeaders.append("Authorization", "App 81db26136740cbeb8af25b4a40d2698a-bcb2cd2c-c5b8-4859-b143-15c607641a70");
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("Accept", "application/json");

    const raw = JSON.stringify({
        "messages": [{
            "from": "447860099299",
            "to": "584144679693",
            "messageId": "222a9da9-a002-4e5a-b018-6006ddb564b3",
            "content": {
                "templateName": "first_purchase_anniversary",
                "templateData": {
                    "body": {
                        "placeholders": ["Javier"]
                    }
                },
                "language": "en"
            }
        }]
    });

    const requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: raw,
        redirect: "follow"
    };

    fetch("https://dkzgm1.api.infobip.com/whatsapp/1/message/template", requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.error(error));
</script>