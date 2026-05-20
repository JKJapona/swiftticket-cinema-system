CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    status ENUM('active', 'banned') DEFAULT 'active',
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    synopsis TEXT, 
    cast_members TEXT, 
    genre VARCHAR(50), 
    runtime_minutes INT, 
    rating ENUM('TBA', 'G', 'PG', 'R-13', 'R-16', 'R-18') DEFAULT 'TBA', 
    poster_path VARCHAR(500),
    cover_path VARCHAR(500),
    trailer_url VARCHAR(255),
    release_date DATE,
    status ENUM('now_showing', 'coming_soon', 'archived') DEFAULT 'coming_soon',
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE cinema_halls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    screen_type ENUM('Standard', 'IMAX', 'Premium', '4DX') DEFAULT 'Standard',
    audio_system VARCHAR(50) DEFAULT 'Dolby Atmos',     
    number_of_rows INT NOT NULL,
    seats_per_row INT NOT NULL,
    total_seats INT AS (number_of_rows * seats_per_row) VIRTUAL,
    status ENUM('Active', 'Maintenance', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE showtimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    hall_id INT NOT NULL,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    price DECIMAL(10, 2) NOT NULL DEFAULT 350.00,
    total_capacity INT NOT NULL,
    booked_seats INT DEFAULT 0,    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (hall_id) REFERENCES cinema_halls(id) ON DELETE CASCADE
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    showtime_id INT NOT NULL,
    reference_number VARCHAR(10) UNIQUE NOT NULL, 
    payment_method ENUM('Pay at Cinema', 'GCash') NOT NULL,
    payment_receipt VARCHAR(255) NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    requested_seats TEXT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'change_requested') DEFAULT 'pending',
    cancellation_reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE
);

CREATE TABLE booked_seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    showtime_id INT NOT NULL,
    seat_code VARCHAR(5) NOT NULL, 
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_seat_per_showtime (showtime_id, seat_code)
);
