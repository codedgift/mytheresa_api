#!/bin/bash

echo "Starting project setup..."

# Step 1: Copy .env file
if [ ! -f .env ]; then
    echo "Copying .env.example to .env..."
    cp .env.example .env
else
    echo ".env file already exists, skipping."
fi

# Step 2: Install Composer dependencies using Sail's Docker environment
if [ ! -d vendor ]; then
    echo "Installing Composer dependencies..."
    docker run --rm -v $(pwd):/app composer install
else
    echo "Composer dependencies already installed, skipping."
fi

# Step 3: Start Sail containers
echo "Starting Laravel Sail containers..."
./vendor/bin/sail up -d

# Step 4: Generate application key
echo "Generating application key..."
./vendor/bin/sail artisan key:generate

# Step 5: Install npm dependencies
echo "Installing npm dependencies..."
./vendor/bin/sail npm install

# Step 6: Run database migrations
echo "Running database migrations..."
./vendor/bin/sail artisan migrate

echo "Setup completed successfully! Visit http://localhost:8080 to access the application."