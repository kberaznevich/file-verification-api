# File Verification Api

## Requirements

  - Docker: Version 20.10 or later
  - Docker Compose: Version 1.29 or later
  - Composer: Version 2.3 or later
  - PHP: Version 8.2 or later (for local development without Docker)

## Getting Started

### Step 1: Clone the Repository

```bash
git clone https://github.com/kberaznevich/file-verification-api.git
cd file-verification-api
```

### Step 2: Set up Environment Variables

```bash
cp src/.env.dev src/.env
```

### Step 3: Build Docker Containers

```bash
docker compose build
```

### Step 3: Run Docker Containers

```bash
docker compose compose up -d
```

### Step 4: Install Composer Dependencies

```bash
docker exec file-verification-api-php composer install
```

### Step 5: Run Migrations

```bash
docker exec file-verification-api-php php artisan migrate --seed
```

## File Verification Api Testing

### Step 1: Setting Up Swagger for API Documentation

#### The File Verification API uses Swagger to document and test the API endpoints. Swagger is configured with the *darkaonline/l5-swagger* package.

### Generate Swagger Documentation
#### To generate the Swagger documentation for the API, run the following Artisan command:

```bash
docker exec file-verification-api-php php artisan l5-swagger:generate
```

#### This will create an interactive API documentation page, which can be accessed at http://localhost/api/documentation

### Viewing API Documentation
#### Once the documentation is generated, you can open the interactive Swagger UI in your browser. This UI allows you to explore the API, view detailed information about each endpoint, and execute requests directly from the browser.

## Key Testing Scenarios

### User Registration Test

- **Scenario**: Test if a user can register with valid credentials.

- **Endpoint**: POST /api/registration

- **Expected Outcome**: A successful login returns a JSON response with an authentication token and user details.

### User Login Test

- **Scenario**: Test if a user can log in with valid credentials.

- **Endpoint**: POST /api/login

- **Expected Outcome**: A successful login returns a JSON response with an authentication token and user details.

### File Verification Test

- **Scenario**: Test file verification.

- **Endpoint**: POST /api/files/verification

- **Expected Outcome**:  The API returns a verification response with the issuer and result.


## Testing API Endpoints with Swagger

#### You can also test the API endpoints directly from the Swagger UI:

1. Navigate to http://localhost/api/documentation.
2. Find the relevant API endpoint (e.g., /api/files/verification).
3. Click on the endpoint to expand the details.
4. Fill in the required parameters.
5. Click the "Execute" button to send a request to the API.
6. View the response directly in the Swagger UI.