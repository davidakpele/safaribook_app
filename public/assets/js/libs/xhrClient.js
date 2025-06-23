const xhrClient = (api_url, method, headers = {}, body = null) => {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open(method, `${api_url}`);

        // Set headers
        Object.keys(headers).forEach((key) => {
            xhr.setRequestHeader(key, headers[key]);
        });

        // Handle response
        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    resolve(response);
                } catch (error) {
                    reject(`Failed to parse response: ${error.message}`);
                }
            }
        };

        // Handle errors
        xhr.onerror = () => reject('Network error occurred.');

        // Send the request
        xhr.send(body ? JSON.stringify(body) : null);
    });
};

export default xhrClient;
