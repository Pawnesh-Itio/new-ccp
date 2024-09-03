<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error</title>
<style>
    /* error.css */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .error-page {
        text-align: center;
        padding: 30px;
        background-color: white;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        max-width: 400px;
        width: 100%;
    }

    .error-page h1 {
        font-size: 10rem;
        margin: 0;
        color: #e84c41;
    }

    .error-page p {
        font-size: 1.2rem;
        color: #666;
        margin: 20px 0;
    }

    .home-button {
        text-decoration: none;
        background-color: #e84c41;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .home-button:hover {
        background-color: #c43c31;
    }
</style>
</head>
<body>
    <div class="error-page">
        <div class="error-content">
            <h1>500</h1>
            <p> Something went wrong, Please try again later.</p>
            <a href="{{ url('/') }}" class="home-button">Go Back Home</a>
        </div>
    </div>
</body>
</html>
