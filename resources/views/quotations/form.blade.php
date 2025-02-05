<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quotation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
        }

        .alert-error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }

        .alert-info {
            background-color: #d9edf7;
            border: 1px solid #bce8f1;
            color: #31708f;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Create Quotation</h1>

        <form action="{{ route('logout') }}" method="POST" style="text-align: right; margin-bottom: 20px;">
            @csrf
            <button type="submit" style="width: auto; background-color: #dc3545;">Logout</button>
        </form>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('quotation'))
        <div class="alert alert-info">
            <h2>Quotation Details:</h2>
            <p>Total: {{ session('quotation')->total }}</p>
            <p>Currency: {{ session('quotation')->currency->code }}</p>
            <p>Start Date: {{ session('quotation')->start_date->format('Y-m-d') }}</p>
            <p>End Date: {{ session('quotation')->end_date->format('Y-m-d') }}</p>
        </div>
        @endif

        <form action="{{ route('quotations.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="ages">Ages (comma separated)</label>
                <input type="text" id="ages" name="ages" value="{{ old('ages') }}" placeholder="25,30,35">
            </div>

            <div class="form-group">
                <label for="currency_id">Currency</label>
                <select id="currency_id" name="currency_id">
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                    <option value="USD">USD</option>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}">
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}">
            </div>

            <button type="submit">Calculate Quotation</button>
        </form>
    </div>
</body>

</html>