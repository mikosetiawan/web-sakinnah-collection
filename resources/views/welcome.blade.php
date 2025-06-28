<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sakinah Collection Costume & Dancer Services</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #1a1a1a;
            color: #f5f5f5;
            font-family: 'Playfair Display', serif;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
        }
        .navbar-brand, .nav-link {
            color: #d4af37 !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #ffffff !important;
        }
        .hero {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            text-align: center;
            overflow: hidden;
        }
        .hero-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }
        .hero-overlay {
            background: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .hero-content {
            z-index: 2;
        }
        .hero h1 {
            font-size: 4rem;
            color: #d4af37;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .hero p {
            font-size: 1.5rem;
            color: #f5f5f5;
        }
        .btn-sakinah {
            background-color: #d4af37;
            color: #1a1a1a;
            border: none;
            padding: 10px 30px;
            font-size: 1.2rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-sakinah:hover {
            background-color: #b8972e;
            transform: scale(1.05);
        }
        .section-title {
            color: #d4af37;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        .card {
            background-color: #2a2a2a;
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-title {
            color: #d4af37;
        }
        .footer {
            background-color: #0f0f0f;
            padding: 2rem 0;
            color: #d4af37;
            text-align: center;
        }
        .modal-content {
            background-color: #2a2a2a;
            color: #f5f5f5;
        }
        .modal-header, .modal-footer {
            border-color: #d4af37;
        }
        .modal-title {
            color: #d4af37;
        }
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            .hero p {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('sakinah_gallery/sakinah_collection_logos2.png') }}" alt="" class="w-25">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-sakinah px-4 py-2" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-sakinah px-4 py-2" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <video class="hero-video" autoplay loop muted playsinline>
            <source src="{{ asset('sakinah_gallery/video-background.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content container">
            <h1>Elevate Your Event</h1>
            <p>Exquisite Costume Booking & Professional Dancer Services by Sakinah Collection</p>
            <a href="#services" class="btn btn-sakinah mt-3">Discover Now</a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <h2 class="section-title">Our Premium Services</h2>
            <div class="row">
                @foreach ($jasas as $jasa)
                    <div class="col-lg-3 col-6 mb-4">
                        <div class="card">
                            <img src="{{ $jasa->image ? asset('storage/' . $jasa->image) : asset('sakinah_gallery/tari-1.jpg') }}" class="card-img-top" alt="{{ $jasa->name }}">
                            <div class="card-body">
                                <h3 class="card-title fw-bold">{{ $jasa->name }}</h3>
                                <h4 class="text-white"><strong>Rp. {{ number_format($jasa->price, 2) }}</strong></h4>
                                <p class="text-white">{{ $jasa->description ?? 'Sewa/performance/pentas' }}</p>
                                <hr>
                                <button class="btn btn-sakinah me-2" data-bs-toggle="modal" data-bs-target="#jasaModal{{ $jasa->id }}">Details</button>
                                @auth
                                    <form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="type" value="jasa">
                                        <input type="hidden" name="id" value="{{ $jasa->id }}">
                                        <button type="submit" class="btn btn-sakinah btn-sm">Add to Cart</button>
                                    </form>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-sakinah btn-sm">Login to Add to Cart</a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Jasa Modal -->
                    <div class="modal fade" id="jasaModal{{ $jasa->id }}" tabindex="-1" aria-labelledby="jasaModalLabel{{ $jasa->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="jasaModalLabel{{ $jasa->id }}">{{ $jasa->name }} Details</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Price:</strong> Rp. {{ number_format($jasa->price, 2) }}</p>
                                    <p><strong>Details:</strong></p>
                                    <p>{{ $jasa->description ?? 'Professional dancer services tailored to your event.' }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sakinah" data-bs-dismiss="modal">Close</button>
                                    @auth
                                        <form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="type" value="jasa">
                                            <input type="hidden" name="id" value="{{ $jasa->id }}">
                                            <button type="submit" class="btn btn-sakinah">Add to Cart</button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sakinah">Login to Add to Cart</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-5">
        <div class="container">
            <h2 class="section-title">Our Premium Products</h2>
            <div class="row">
                @foreach ($barangs as $barang)
                    <div class="col-lg-3 col-6 mb-4">
                        <div class="card">
                            <img src="{{ $barang->image ? asset('storage/' . $barang->image) : asset('sakinah_gallery/tari-1.jpg') }}" class="card-img-top" alt="{{ $barang->name }}">
                            <div class="card-body">
                                <h3 class="card-title fw-bold">{{ $barang->name }}</h3>
                                <h4 class="text-white"><strong>Rp. {{ number_format($barang->price, 2) }}</strong></h4>
                                <p class="text-white">{{ $barang->description ?? 'Costume rental for events.' }}</p>
                                <hr>
                                <button class="btn btn-sakinah me-2" data-bs-toggle="modal" data-bs-target="#barangModal{{ $barang->id }}">Details</button>
                                @auth
                                    <form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="type" value="barang">
                                        <input type="hidden" name="id" value="{{ $barang->id }}">
                                        <button type="submit" class="btn btn-sakinah btn-sm">Add to Cart</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sakinah btn-sm">Login to Add to Cart</a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Barang Modal -->
                    <div class="modal fade" id="barangModal{{ $barang->id }}" tabindex="-1" aria-labelledby="barangModalLabel{{ $barang->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="barangModalLabel{{ $barang->id }}">{{ $barang->name }} Details</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Price:</strong> Rp. {{ number_format($barang->price, 2) }}</p>
                                    <p><strong>Details:</strong></p>
                                    <p>{{ $barang->description ?? 'High-quality costume for your event.' }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sakinah" data-bs-dismiss="modal">Close</button>
                                    @auth
                                        <form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="type" value="barang">
                                            <input type="hidden" name="id" value="{{ $barang->id }}">
                                            <button type="submit" class="btn btn-sakinah">Add to Cart</button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sakinah">Login to Add to Cart</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-dark">
        <div class="container">
            <h2 class="section-title">Get in Touch</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Your Name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Your Email">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="4" placeholder="Your Message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sakinah w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>© {{ date('Y') }} Sakinah Collection. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>