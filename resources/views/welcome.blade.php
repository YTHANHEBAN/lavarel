<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travelo - Travel Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .hero {
            background: url('https://source.unsplash.com/1600x600/?travel') no-repeat center center/cover;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        .booking-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Travelo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Flights</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cars</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cruises</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero">
        <div>
            <h1>Welcome to World's No. 1 Travel Booking Platform</h1>
            <p>Book your dream trip today!</p>
            <button class="btn btn-primary">Buy Now</button>
            <button class="btn btn-outline-light">More Info</button>
        </div>
    </div>

    <div class="container mt-4">
        <div class="booking-form p-4 shadow">
            <form>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Destination</label>
                        <input type="text" class="form-control" placeholder="Enter destination">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Check-in</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Check-out</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Adults</label>
                        <input type="number" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kids</label>
                        <input type="number" class="form-control" value="0" min="0">
                    </div>
                </div>
                <button class="btn btn-success mt-3 w-100">Search Now</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
