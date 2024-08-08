# Election Results Collation System

## Overview

This project is a web-based application for managing and collating election results. It allows users to:

1. **View Results for a Specific Polling Unit**
2. **Summarize Results for All Polling Units in a Selected Local Government Area (LGA)**
3. **Store Results for New Polling Units**

The system is designed using PHP and MySQL and includes API endpoints to handle data retrieval and manipulation.

## Project Structure

- **`/public`**: Contains the public-facing files, including the front-end code (HTML, CSS, JavaScript).
- **`/app`**: Contains the application logic, including controllers and models.
- **`/app/lib`**: Includes library files such as the database connection class.
- **`/app/controllers`**: Contains the API controllers for handling requests.
- **`/app/models`**: Contains the models for interacting with the database.
- **`/app/views`**: Contains the view files for rendering pages.

## Setup and Installation

### Prerequisites

- PHP (version 7.4 or later)
- MySQL (version 5.7 or later)
- Web server (e.g., Apache, Nginx)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/election-results-collation.git
   cd election-results-collation

2. **Configure the Database**
   - Import the database schema and data from bincom_test.sqi into your MySQL server.
   - Update the database credentials in app/lib/Database.php.

3. **Set Up the Web Server**
   - Configure your web server to point to the public directory of the project.

4. **Access the Application**
   - Navigate to http://yourserver in your web browser to access the application.

## API Endpoints
1. **Collate Results**
   - **Endpoints**: `/api/collate`
   - **Method**: `POST`
   - **Parameters**:
      - `ward` (int)
      - `polling_unit` (string)
      - `party` (string)
      - `vote` (int)
2. **Get Polling Units by LGA**
   - **Endpoints**: `/api/polling_units_by_lga`
   - **Method**: `GET`
   - **Parameters**:
      - `lga_id` (int)
3. **Get Wards by LGA**
   - **Endpoints**: `/api/wards_by_lga`
   - **Method**: `GET`
   - **Parameters**:
      - `lga_id` (int)

## Usage
1. **Select LGA**:Use the dropdown to select an LGA.
2. **Load Wards**:The wards will be dynamically loaded based on the selected LGA.
3. **Select Ward and Polling Unit**:Choose a ward and polling unit.
4. **Submit Results**:Enter the results for the selected polling unit and submit.

## Contributing
1. Fork the repository.
2. Create a new branch (git checkout -b feature/YourFeature).
3. Make your changes and commit them (git commit -am 'Add new feature').
4. Push to the branch (git push origin feature/YourFeature).
5. Create a new Pull Request.
