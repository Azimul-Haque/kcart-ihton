<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCS Exam Aid API Status</title>
    <meta name="description" content="BCS Exam Aid বাংলাদেশ সিভিল সার্ভিস (BCS) পরীক্ষা এবং অন্যান্য সরকারি চাকরি (NSI, DGFI, দুদক, বাংলাদেশ ব্যাংক, অন্যান্য ব্যাংক, প্রাথমিক শিক্ষক নিয়োগ) পরীক্ষার প্রস্তুতির জন্য সেরা ডেডিকেটেড অনলাইন প্ল্যাটফর্ম। Developed By A. H. M. Azimul Haque.">
    <meta name="keywords" content="BCS, Bangladesh Civil Service, NSI, DGFI দুদক, বাংলাদেশ ব্যাংক, অন্যান্য ব্যাংক, প্রাথমিক শিক্ষক নিয়োগ, Primary Exam, Job Circular, Bank Job Circular, Bank Job Exam, BCS Circular, বিসিএস পরীক্ষা, বার কাউন্সিল পরীক্ষা, জুডিশিয়াল পরীক্ষা, Judicial Exam, bcs book list, bcs book suggestion, BCS Preparation Books, বিসিএস প্রিলিমিনারি বই তালিকা, বিসিএস বই তালিকা, বিসিএস লিখিত বই তালিকা, bcs preliminary book list, bcs written book list, বিসিএস প্রিলিমিনারি পরীক্ষার সিলেবাস, বিসিএস পরীক্ষার সিলেবাস">

    <!-- Load Bootstrap v5.0.1 CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-+0n0xVW2eSR5OomGNYDnhZAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('images/favicon.png') }}">
    <meta name="theme-color" content="#155BD5">
    <meta name="msapplication-navbutton-color" content="#155BD5">
    <meta name="apple-mobile-web-app-status-bar-style" content="#155BD5">
    <meta name="author" content="A. H. M. Azimul Haque">
    <!-- Custom styling for better presentation and font -->
    <style>
        body {
            background-color: #f0f4f8; /* Light blue-gray background */
            font-family: 'Inter', sans-serif;
        }
        .header-section {
            background-color: #007bff; /* Primary blue for header */
            color: white;
            padding: 2rem 0;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .status-card {
            transition: all 0.3s ease-in-out;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            height: 100%;
            border-left: 5px solid transparent; /* Placeholder for status border */
        }
        .status-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5em 1em;
            border-radius: 0.5rem;
            min-width: 120px;
            text-align: center;
            font-weight: 600;
        }
        .status-success { border-left-color: #198754 !important; }
        .status-warning { border-left-color: #ffc107 !important; }
        .status-danger { border-left-color: #dc3545 !important; }
        .status-secondary { border-left-color: #6c757d !important; }
    </style>
      <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "Website",
        "headline": "BCS Exam Aid - API Status",
        "description": "Check the real-time operational status of the official BCS Exam Aid application APIs. Monitor the health and uptime of core services,",
        "image": "{{ asset('images/bcs-exam-aid-banner.png') }}",
        "url": "{{ url()->current() }}",
        "author": {
            "@type": "Person",
            "name": "A. H. M. Azimul Haque"
          }
        }
    </script>
</head>
<body>

    <div class="header-section mb-5">
        <div class="container text-center">
            <a class="navbar-brand" href="/">
              <img src="{{ asset('/') }}images/white-logo.png" alt="BCS Exam Aid Logo" />
            </a>
            <h1 class="display-5 fw-bold">BCS Exam Aid - API Status</h1>
            <p class="lead">Monitoring core services for a seamless BCS preparation experience.</p>
        </div>
    </div>

    <div class="container my-5">
        <!-- Status Cards Container -->
        <div class="row row-cols-1 row-cols-md-3 g-4" id="api-status-container">
            <!-- Cards will be populated here by JavaScript -->
        </div>

        <div class="text-center mt-5 pt-3">
            <button class="btn btn-lg btn-primary shadow-sm" onclick="checkAllStatuses()">
                Refresh Status
            </button>
            <p class="text-muted mt-3 small">Last check: <span id="last-updated" class="fw-semibold">N/A</span></p>
            <!-- <p class="text-muted small">All times are local.</p> -->
        </div>
    </div>

    <!-- Load Bootstrap v5.0.1 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0t2dF9tI7R3gwef46NqM33hR9A8L+6U/lP/lB" crossorigin="anonymous"></script>

    <script>
        // --- Configuration: Define your BCS Exam Aid API services here ---
        const services = [
            { 
                id: 'auth', 
                name: 'User & Authentication Service', 
                endpoint: '/api/v1/auth', 
                description: 'Handles user registration, login, and profile data.',
                // Mock codes for demonstration: 200 (Operational), 404 (Degraded/Error), 500 (Down)
                mockCode: 200, 
                latency: 1000 
            },
            { 
                id: 'question_bank', 
                name: 'Question Bank & Categories API', 
                endpoint: '/api/v1/questions', 
                description: 'Provides access to the BCS preparation question database.',
                mockCode: 200, // Simulating a server error for demonstration
                latency: 1500 
            },
            { 
                id: 'analytics', 
                name: 'Result & Analytics Service', 
                endpoint: '/api/v1/results', 
                description: 'Stores and calculates progress, scores, and mock test results.',
                mockCode: 200, 
                latency: 500 
            }
        ];
        // -----------------------------------------------------------------

        const container = document.getElementById('api-status-container');

        /**
         * Simulates an API status check with latency and a predefined response code.
         * In a real application, this function MUST be replaced with a proper `fetch()` call.
         * @param {object} service - The service object.
         * @returns {Promise<number>} - A Promise that resolves with a simulated HTTP status code.
         */
        function mockFetchStatus(service) {
            return new Promise(resolve => {
                // To implement real API checks, replace the setTimeout block with:
                /*
                fetch(service.endpoint)
                    .then(response => resolve(response.status))
                    .catch(() => resolve(999)); // 999 for network error
                */
                setTimeout(() => {
                    resolve(service.mockCode);
                }, service.latency);
            });
        }

        /**
         * Renders the initial card structure for a service.
         * @param {object} service - The service object.
         */
        function renderServiceCard(service) {
            const col = document.createElement('div');
            col.className = 'col';
            col.innerHTML = `
                <div class="card status-card" id="${service.id}-card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-dark">${service.name}</h5>
                        <p class="card-text text-muted small mb-3">${service.description}</p>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-secondary fw-semibold">Endpoint: ${service.endpoint}</small>
                            <span id="${service.id}-status" class="badge bg-secondary status-badge">
                                Checking...
                            </span>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(col);
        }

        /**
         * Updates the status badge and card border based on the HTTP response code.
         * @param {string} serviceId - The ID of the service element to update.
         * @param {number} statusCode - The simulated HTTP status code.
         */
        function updateStatusCard(serviceId, statusCode) {
            const statusElement = document.getElementById(`${serviceId}-status`);
            const cardElement = document.getElementById(`${serviceId}-card`);
            let statusText;
            let badgeClass;
            let cardClass;

            // Determine status based on common HTTP conventions
            if (statusCode >= 200 && statusCode < 300) {
                statusText = 'OPERATIONAL';
                badgeClass = 'bg-success';
                cardClass = 'status-success';
            } else if (statusCode >= 400 && statusCode < 500) {
                statusText = 'OPERATIONAL';
                badgeClass = 'bg-success text-dark';
                cardClass = 'status-warning';
            } else if (statusCode >= 500) {
                statusText = 'MAJOR OUTAGE';
                badgeClass = 'bg-danger';
                cardClass = 'status-danger';
            } else { // Fallback for network error (simulated as 999)
                statusText = 'UNKNOWN/NETWORK FAIL';
                badgeClass = 'bg-danger';
                cardClass = 'status-danger';
            }

            // Apply classes
            statusElement.className = `badge ${badgeClass} status-badge`;
            statusElement.textContent = statusText;

            // Remove old status class and add new one
            cardElement.classList.remove('status-success', 'status-warning', 'status-danger', 'status-secondary');
            cardElement.classList.add(cardClass);
        }

        /**
         * Fetches status for all services and updates the UI.
         */
        async function checkAllStatuses() {
            const updateTimeElement = document.getElementById('last-updated');

            // 1. Reset all statuses to "Checking..."
            services.forEach(service => {
                const statusElement = document.getElementById(`${service.id}-status`);
                const cardElement = document.getElementById(`${service.id}-card`);
                if (statusElement) {
                    statusElement.className = 'badge bg-secondary status-badge';
                    statusElement.textContent = 'Checking...';
                    cardElement.classList.add('status-secondary');
                }
            });

            // 2. Check each service concurrently
            const statusChecks = services.map(async (service) => {
                try {
                    // IMPORTANT: In production, change mockFetchStatus to a real fetch call.
                    const statusCode = await mockFetchStatus(service);
                    updateStatusCard(service.id, statusCode);
                } catch (error) {
                    console.error(`Error checking ${service.name}:`, error);
                    updateStatusCard(service.id, 999); // Use 999 for network/unknown error
                }
            });

            // Wait for all checks to complete
            await Promise.all(statusChecks);

            // 3. Update the last checked time
            updateTimeElement.textContent = new Date().toLocaleTimeString();
        }

        // Initialize the dashboard structure and trigger the first status check on load
        window.onload = function() {
            services.forEach(renderServiceCard);
            checkAllStatuses();
        };

    </script>
</body>
</html>
